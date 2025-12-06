<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>AzeUnlock FRP Tool</title>
  <link rel="icon" href="pngegg.png" type="image/x-icon">
  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1e40af;
      --bg: #f1f5f9;
      --glass: rgba(255, 255, 255, 0.75);
      --border: rgba(255, 255, 255, 0.3);
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Arial, sans-serif;
    }
    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: var(--bg);
      background-image: radial-gradient(circle at top left, #e0ecff, transparent 40%),
                        radial-gradient(circle at bottom right, #e0ecff, transparent 40%);
    }

    /* ==== Container Glass Card ==== */
    .container {
      width: 420px;
      background: var(--glass);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: 0 8px 26px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      animation: fadeScale 0.5s ease;
    }
    @keyframes fadeScale {
      from { opacity: 0; transform: scale(0.96);}
      to { opacity: 1; transform: scale(1);}
    }

    .header {
      text-align: center;
      padding: 20px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: #fff;
    }
    .header h1 {
      font-size: 21px;
      font-weight: 700;
      margin-bottom: 5px;
    }
    .header p {
      font-size: 13px;
      opacity: 0.9;
    }

    .form {
      padding: 22px 25px;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }
    input {
      width: 100%;
      padding: 12px;
      border-radius: 12px;
      border: 1px solid #dbeafe;
      background: #fff;
      font-size: 14px;
      color: #1e293b;
      transition: 0.2s ease;
    }
    input:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 6px rgba(37, 99, 235, 0.25);
    }

    button {
      padding: 12px;
      border-radius: 10px;
      background: var(--primary);
      color: #fff;
      font-weight: 600;
      border: none;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s ease;
    }
    button:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    }

    .log {
      margin: 15px 20px 20px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      padding: 14px;
      border-radius: 10px;
      font-family: Consolas, monospace;
      font-size: 13px;
      color: #334155;
      max-height: 160px;
      overflow-y: auto;
      animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn {
      from {opacity:0; transform: translateY(6px);}
      to {opacity:1; transform: translateY(0);}
    }
    .log div { margin: 4px 0; }
    .green { color: #16a34a; font-weight: 600; }
    .red { color: #dc2626; font-weight: 600; }
    .blue { color: #2563eb; font-weight: 600; word-break: break-word; }

  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>AzeUnlock FRP Tool</h1>
      <p>Xiaomi Assistant Mode FRP Unlock</p>
    </div>

    <div class="form">
      <input type="text" id="username" placeholder="Username" />
      <input type="password" id="password" placeholder="Password" />
      <button id="readinfobtn">Start FRP Unlock</button>
    </div>

    <div class="log" id="log">
      <div class="blue">waiting for device...</div>
    </div>
  </div>

<script src="recovery.js"></script>
<script>
let usbdevice = null;
let adbinterface = null;

document.getElementById('readinfobtn').addEventListener('click', async () => {
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  if (!username || !password) {
    richlogs('Please enter both username and password.', true, 'red');
    return;
  }
  clearlog();
  richlogs('Connecting to server...', true);
  await checkcredit(username, password);
});

async function checkcredit(username, password) {
  const res = await fetch(`azegsm.com/apibridge/?username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
  if (!res.ok) {
    richlogs(`Server error ${res.status}`, true, 'red');
    return;
  }
  const data = await res.json();
  if (data.status === 'success') {
    richlogs('Login success', true, 'green');
    richlogs(`Username: `, true); richlogs(username, false, 'blue');
    richlogs(`Credit: `, true); richlogs(data.credit + ' credits', false, 'blue');
    richlogs('Connecting to device...', true);

    usbdevice = new WebUSBDevice({ classCode: 255, subclassCode: 66, protocolCode: 1 });
    let success = await usbdevice.requestDevice();
    if (success && await usbdevice.connect()) {
      try {
        adbinterface = new WebUSBADB(usbdevice);
        if (await adbinterface.connect()) {
          richlogs('Device connected', true, 'green');
          await readinfo(username, password);
        } else richlogs('Failed to connect ADB', true, 'red');
      } catch (err) {
        logmessage('Error: ' + err.message);
      } finally {
        if (usbdevice) await usbdevice.disconnect();
        logmessage('Device disconnected.');
      }
    } else richlogs('Device connection failed', true, 'red');
  } else {
    richlogs(data.message || 'Auth failed', true, 'red');
  }
}

async function readinfo(username, password) {
  const commands = [
    { label: 'Device Name', command: 'getdevice:' },
    { label: 'Device Version', command: 'getversion:' },
    { label: 'Device SN', command: 'getsn:' },
    { label: 'Device Token', command: 'getmitoken:' }
  ];
  for (const item of commands) {
    let res = await adbinterface.sendRecoveryCommand(item.command);
    if (res) {
      if (item.label === 'Device Token') {
        logkeyvalue(item.label, res);
        richlogs('Getting authentication...', true);
        await getauth('recovery', res, username, password);
      } else logkeyvalue(item.label, res);
    } else logkeyvalue(item.label, adbinterface.lastError || 'N/A');
  }
}

async function getauth(type, key, username, password) {
  try {
    const res = await fetch(`azegsm.com/apibridge/?auth_type=${encodeURIComponent(type)}&auth_key=${encodeURIComponent(key)}&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
    if (!res.ok) throw new Error(`Server error ${res.status}`);
    const data = await res.json();
    if (data.code === 0 && data.encryptData) {
      richlogs('Authentication success', true, 'green');
      richlogs('Formatting FRP...', true);
      let resp = await adbinterface.sendRecoveryCommand('format-frp:' + String(data.encryptData));
      richlogs(resp, true, 'green');
      richlogs('Rebooting device...', true);
      await adbinterface.sendRecoveryCommand('reboot:');
      richlogs('Done', true, 'green');
    } else richlogs(data.descEN || 'Authentication failed', true, 'red');
  } catch (error) {
    logmessage('Error: ' + error.message);
  }
}

function richlogs(msg, newline = true, color = '#000') {
  const log = document.getElementById('log');
  if (newline) {
    const line = document.createElement('div');
    line.textContent = msg;
    line.style.color = color;
    log.appendChild(line);
  } else {
    const last = log.lastElementChild;
    if (last) {
      const span = document.createElement('span');
      span.textContent = ' ' + msg;
      span.style.color = color;
      last.appendChild(span);
    }
  }
  log.scrollTop = log.scrollHeight;
}

function logkeyvalue(label, value) {
  const log = document.getElementById('log');
  const line = document.createElement('div');
  line.innerHTML = `<span>${label.padEnd(15,' ')} : </span><span class="green">${value}</span>`;
  log.appendChild(line);
  log.scrollTop = log.scrollHeight;
}

function logmessage(msg) { richlogs(msg, true, 'red'); }
function clearlog() { document.getElementById('log').innerHTML = ''; }
</script>
</body>
</html>
