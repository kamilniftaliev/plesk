<?php
include "../includes/konak.php";
session_start();
$login = "";
if ($_SESSION['status'] == "login") {
  $login = true;
  $id = $_SESSION['id'];

  $data = mysqli_query($koneksi, "SELECT * FROM user  where id = '$id' ");
  $user = mysqli_fetch_array($data);
  $apikey = $user['apikey'];
}
?>

<!DOCTYPE HTML>
<html>

<head>
  <script src="./webadb.js"></script>
  <script>
    let adb;
    let webusb;

    let log = (...args) => {
      if (args[0] instanceof Error) {
        console.error.apply(console, args);
      } else {
        console.log.apply(console, args);
      }
      document.getElementById('log').innerText += args.join(' ') + '\n';
    };

    let init = async () => {

      webusb = await Adb.open("WebUSB");
    };

    let connect = async () => {
      document.getElementById('log').innerText = '';
      await init();
      log('connecting');
      if (webusb.isAdb()) {
        try {
          adb = null;
          adb = await webusb.connectAdb("host::", () => {
            log("Please check the screen of your " + webusb.device.productName + ".");
          });
        } catch (error) {
          log(error);
          adb = null;
        }
        let shell = await adb.cmd("getmitoken:");
        let response = await shell.receive();
        let decoder = new TextDecoder('utf-8');
        let txt = decoder.decode(response.data);
        token = txt.replace("\n", "");
        //  log(txt);
        shell = await adb.cmd("getdevice:");
        response = await shell.receive();
        decoder = new TextDecoder('utf-8');
        txt = decoder.decode(response.data);
        //	log(txt);
        devname = txt.replace("\n", "")
        prodname = "XIAOMI GLOBAl";
        const configdata = JSON.stringify({
          productName: prodname,
          deviceName: devname,
          token: token
        });

        //	  log(configdata);
        var encodedString = btoa(configdata);
        // log(encodedString);
        var auth = "";
        let OTP = document.getElementById('port').value;
        var details = {
          'OTP': OTP,
          'configblob': encodedString,
          'serviceId': '2'
        };

        var formBody = [];
        for (var property in details) {
          var encodedKey = encodeURIComponent(property);
          var encodedValue = encodeURIComponent(details[property]);
          formBody.push(encodedKey + "=" + encodedValue);
        }
        formBody = formBody.join("&");
        const authdata = ["Saab", "Volvo"];


        log("Call Mi auth , please wwait......");
        //const asyncPostCall = async () => {
        try {
          const response = await fetch('https://frp62.id/xiaomi/frp.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
            },
            body: formBody
          });
          const data = await response.json();
          authdata[0] = data.status;
          authdata[1] = data.message;
        } catch (error) {


          console.log(error)
        }
        //     }


        var result = authdata[0].localeCompare("OK");
        if (result == 0) {

          log("GENERATED AUTH OK");
          log("EXEC AUTH");
          shell = await adb.cmd("format-frp:" + authdata[1]);
          response = await shell.receive();
          decoder = new TextDecoder('utf-8');
          txt = decoder.decode(response.data);
          log(txt);

          shell = await adb.cmd("reboot:");
          response = await shell.receive();
          decoder = new TextDecoder('utf-8');
          txt = decoder.decode(response.data);
          log(txt);
        } else {

          log(authdata[1]);


        }

      }
    };

    let disconnect = async () => {
      log('disconnect');
      webusb.close();
    };

    let get_ip = async () => {
      try {
        if (!adb) throw new Error('Not connected');
        log('get_ip');
        let shell = await adb.shell('ip addr show to 0.0.0.0/0 scope global');
        let response = await shell.receive();
        let decoder = new TextDecoder('utf-8');
        let txt = decoder.decode(response.data);
        log(txt);
      } catch (error) {
        log(error);
      }
    };

    let tcpip = async () => {
      try {
        if (!adb) throw new Error('Not connected');
        let port = document.getElementById('port').value;
        log('requesting tcpip mode on port', port);
        await adb.tcpip(port);
        log('tcpip connection ready');
      } catch (error) {
        log(error);
      }
    };

    let add_ui = () => {
      // Adb.Opt.use_checksum = false;
      Adb.Opt.debug = true;
      // Adb.Opt.dump = true;

      document.getElementById('connect').onclick = connect;
      document.getElementById('get_ip').onclick = get_ip;
      document.getElementById('disconnect').onclick = disconnect;
      document.getElementById('tcpip').onclick = tcpip;

      document.getElementById('clear').onclick = () => {
        document.getElementById('log').innerText = '';
      };
    };

    document.addEventListener('DOMContentLoaded', add_ui, false);
  </script>
</head>

<body>
  <center>
    <input type="password" id="port" value="<?php echo $apikey; ?>" />
    <label id="tcpip" value="tcpip">OTP</label>
    <br />
    <br />


    <button id="connect">CLEAR FRP</button>
    <br />
    <br />

    <pre id="log"></pre>
  </center>


</body>

</html>