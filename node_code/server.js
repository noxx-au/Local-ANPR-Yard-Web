'use strict'
const https = require('https');
const fs = require('fs');
const ws = require('ws');
const options = {
  key: fs.readFileSync('key.pem'),
  cert: fs.readFileSync('cert.pem')
};
const WebSocket = require('ws')
 
//const wss = new WebSocket.Server({ port: 8000 })
 
let server = https.createServer(options, (req, res) => {
  res.writeHead(200);
  res.end(index);
});
server.listen(8282, () => console.log('Https running on port 8282'));

const wss = new ws.Server({server, path: '/'});

wss.on('connection', function connection(ws, req) {

  ws.on('message', function incoming(data, isBinary) {
    wss.clients.forEach(function each(client) {
    var  client_data =  JSON.parse(data);
        if (client_data.type=='socket' && client.user_id==undefined)
        {
          client.user_id=client_data.user_id;
        }
      if ((client_data.recipient_id == null ||client.user_id==client_data.recipient_id) && client !== ws && client.readyState === WebSocket.OPEN && client_data.type=='chat') {
        client.send(data, { binary: isBinary });
      }
    });
  });
});