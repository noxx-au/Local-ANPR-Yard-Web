 process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';
const WebSocket = require('ws')
var convert = require('xml-js');
const url = 'wss://api.noxx.com.au:8282'
const connection = new WebSocket(url)
var client_data = '';
 
connection.onopen = () => {
	var client = {
                user_id: 1,
                recipient_id: null,
                type: 'socket',
                token: null,
                message: null
            };
  connection.send(JSON.stringify(client)) 
}
 
connection.onerror = (error) => {
  console.log(`WebSocket error: ${JSON.stringify(error)}`)
}

connection.onmessage = (e) => {
	///call weight api
	// send weight result to server
    console.warn(e.data);
	client_data =  JSON.parse(e.data);
	var user_id = client_data.user_id;
	
	client_data.user_id="1";
	client_data.recipient_id=user_id;
  weight_data(function(results){
    client_data.message=results;
    connection.send(JSON.stringify(client_data))
  });
  //weight_data();
  //connection.send(JSON.stringify(client_data))

}

var axios = require('axios');

var config = {
    }
function weight_data(callback) {
    const headers = {
        'Content-Type': 'application/xml',
        'token':'e2fc714c4727ee9395f324cd2e7f331f',
        'x-api-key':'0cc175b9c0f1b6a831c399e269772661'
    }
    axios.post('https://api.noxx.com.au/api/xml/requestAccess', config, {
      headers: headers
    })
    .then(function(response) {
        res = response.data;
        var xml2json = convert.xml2json(res.Message, {compact: true, spaces: 4});
        var obj = JSON.parse(xml2json);
        var data = obj.TruckWeighingResponseData;
        var Group1_weight = data.Group1.GroupWeight._text;
        var Group2_weight = data.Group2.GroupWeight._text;
        var Group3_weight = data.Group3.GroupWeight._text;
        var Group4_weight = data.Group4.GroupWeight._text;
        var Group5_weight = data.Group5.GroupWeight._text;
        var Group6_weight = data.Group6.GroupWeight._text;
        var marge_weight = [Group1_weight, Group2_weight, Group3_weight, Group4_weight, Group5_weight, Group6_weight];
        callback(JSON.stringify(marge_weight));
    }).catch(error => {
      callback('samethig wrong');
   });
}
