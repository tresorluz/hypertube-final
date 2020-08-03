'use strict'

const express = require('express');
const app = express();
const request = require('request');
const path = require('path');
const fs = require('fs');
const stream = require('./stream');
const subtitles = require('./subtitles');
const port = 3000 || process.env.PORT;

// app.use(express.static(path.join(__dirname, '../public')));

app.use(function (req, res, next) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');
    res.setHeader('Access-Control-Allow-Credentials', true);
    res.setHeader('content-type', 'text/vtt');
    next();
});

let torrentHash = {};
let currentMovieUrl = '';
let currentIMDB = '';

app.get('/', (req, res) => {
  res.send('index.php')
})


app.get('/stream/:hash', (req, res) => {
  console.log('Stream: ', req.params.hash);
    let tmpReq = req;
    let hash =  req.params.hash;
    setTimeout(function () {
        if (hash) {
            stream.magnetUrl(req, res, hash);
        }
        else {
            res.send("error");
        }
    }, 1000);
});

app.get('/subtitles/:id/:lang/:season/:episode',  (req, res) => {
  let tmpReq = req;
  console.log('subtitles id: ',req.params.id, 'lang: ', req.params.lang);
  setTimeout(function() {
    subtitles.getSubtitles(res, tmpReq.params.id, tmpReq.params.lang, tmpReq.params.season, tmpReq.params.episode);
  }, 2000);
});

app.listen(3000,  () => {
    console.log(`Server started on port ${port}`);
});
