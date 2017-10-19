var http = require('http');
var url = require('url');
var fs = require('fs');
http.createServer(function (req, res) {
    res.writeHead(200, {'Content-Type': 'application/json; charset=utf-8', 'Cache-Control': 'no-cache, must-revalidate'});
    var reqUrl = url.parse(req.url);
    console.log(reqUrl);
    if (reqUrl.pathname == "/favicon.ico") {
        fs.readFile('favicon.ico', function(err, data) {
            res.writeHead(200, {'Content-Type': 'image/ico'});
            res.write(data);
            res.end();
        });
    } else {
        
        res.end();
    }

}).listen(8080);
