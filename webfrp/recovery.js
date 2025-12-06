class WebUSBDevice {
    constructor(deviceFilter) {
        this.usbDevice = null;
        this.filter = [deviceFilter];
        this.endpointIn = null;
        this.endpointOut = null;
        this.lastError = "";
    }

    async requestDevice() {
        if (!navigator.usb) {
            this.lastError = "Unsupported browser / OS";
            return false;
        }
        try {
            this.usbDevice = await navigator.usb.requestDevice({ filters: this.filter });
            return !!this.usbDevice;
        } catch (error) {
            this.lastError = error.message;
            return false;
        }
    }

    async claimInterface() {
        const findEndpoint = (endpoints, direction) => {
            for (let endpoint of endpoints) {
                if (endpoint.direction === direction && endpoint.type === "bulk") {
                    return endpoint.endpointNumber;
                }
            }
            throw new Error(`Cannot find ${direction} endpoint`);
        };

        let matchedInterface = null;
        for (let config of this.usbDevice.configurations) {
            for (let iface of config.interfaces) {
                for (let alt of iface.alternates) {
                    if (
                        this.filter[0].classCode === alt.interfaceClass &&
                        this.filter[0].subclassCode === alt.interfaceSubclass &&
                        this.filter[0].protocolCode === alt.interfaceProtocol
                    ) {
                        matchedInterface = { conf: config, intf: iface, alt: alt };
                        break;
                    }
                }
            }
        }

        if (!matchedInterface) {
            this.lastError = "Endpoint match not found";
            return false;
        }

        await this.usbDevice.selectConfiguration(matchedInterface.conf.configurationValue);
        await this.usbDevice.claimInterface(matchedInterface.intf.interfaceNumber);
        this.endpointIn = findEndpoint(matchedInterface.alt.endpoints, "in");
        this.endpointOut = findEndpoint(matchedInterface.alt.endpoints, "out");
        return true;
    }

    async connect() {
        try {
            await this.usbDevice.open();
            return await this.claimInterface();
        } catch (error) {
            this.lastError = error.message;
            return false;
        }
    }

    async send(data) {
        return await this.usbDevice.transferOut(this.endpointOut, data);
    }

    async recv(length) {
        return await this.usbDevice.transferIn(this.endpointIn, length);
    }

    async disconnect() {
        await this.usbDevice.close();
    }
}

class WebUSBADB {
    constructor(usbDevice) {
        this.usbDevice = usbDevice;
        this.lastError = "";
        this.model = "";
    }

    adbChecksum(buffer) {
        let sum = 0;
        for (let byte of buffer) {
            sum += byte;
        }
        return sum & 0xffffffff;
    }

    async sendCommand(command, arg0, arg1, data = null) {
        if (typeof data === "string") {
            data = new TextEncoder().encode(data);
        } else if (ArrayBuffer.isView(data)) {
            data = data.buffer;
        }

        let dataSize = data ? data.byteLength : 0;
        let checksum = this.adbChecksum(new Uint8Array(data || []));
        let packet = new ArrayBuffer(24);
        let view = new DataView(packet);
        
        view.setUint32(0, command, true);
        view.setUint32(4, arg0, true);
        view.setUint32(8, arg1, true);
        view.setUint32(12, dataSize, true);
        view.setUint32(16, checksum, true);
        view.setUint32(20, ~command >>> 0, true);

        let response = await this.usbDevice.send(packet);
        if (dataSize > 0) {
            response = await this.usbDevice.send(data);
        }

        return response.status === "ok";
    }

    async recvCommand() {
        let response = await this.usbDevice.recv(24);
        if (response.status !== "ok") {
            this.lastError = "Failed to receive command header";
            return false;
        }

        let view = new DataView(response.data.buffer);
        let cmd = view.getUint32(0, true);
        let dataSize = view.getUint32(12, true);
        let data = null;

        if (dataSize > 0) {
            let dataResponse = await this.usbDevice.recv(dataSize);
            data = dataResponse.data.buffer;
        }

        return { cmd, data };
    }

    async connect() {
        if (!await this.sendCommand(1314410051, 16777217, 1048576, "host::\0")) {
            this.lastError = "Failed to send connect command";
            return false;
        }

        let retries = 10;
        let response = null;

        while (retries > 0) {
            response = await this.recvCommand();
            if (!response) return false;
            if (response.cmd === 1314410051) break;
            retries -= 1;
        }

        let responseText = new TextDecoder().decode(new Uint8Array(response.data)).toString("ascii");
        let properties = responseText.slice(10).split(";");
        
        properties.forEach(prop => {
            if (prop.startsWith("ro.product.model")) {
                this.model = prop.split("=")[1];
            }
        });

        return true;
    }

    async sendRecoveryCommand(command) {
        command += "\0";
        if (!await this.sendCommand(1313165391, 1, 0, command)) {
            return false;
        }

        await this.recvCommand();
        let response = await this.recvCommand();
        if (!response) return false;

        await this.recvCommand();
        return new TextDecoder().decode(new Uint8Array(response.data));
    }
}
