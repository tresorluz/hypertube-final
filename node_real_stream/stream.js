const torrentStream = require('torrent-stream');
const fs = require('fs');
const pump = require('pump');
const rimraf = require("rimraf");

function magnetUrl (req, res, torrentLink) {
  let fullPath = '../public/film/'  + torrentLink + ".mp4.part";
  let newpath = '../public/film/'  + torrentLink + ".mp4";

  fs.exists(newpath, (exists) => {
    if (exists) {
        stream(req, res, newpath);
    } else {
      let opts =
      {
        connections: 100,         // Max amount of peers to be connected to.
        uploads: 10,              // Number of upload slots.
      	tmp: '/tmp',              // Root folder for the files storage.
                                  // Defaults to '/tmp' or temp folder specific to your OS.
                                  // Each torrent will be placed into a separate folder under /tmp/torrent-stream/{infoHash}
      	path: '../public/film/' + torrentLink +'_tmp',   // Where to save the files. Overrides `tmp`.
      	verify: true,             // Verify previously stored data before starting
                                  // Defaults to true
      	dht: true,                // Whether or not to use DHT to initialize the swarm.
                                  // Defaults to true
      	tracker: true,            // Whether or not to use trackers from torrent file or magnet link
                                  // Defaults to true
      	trackers: [],             // Allows to declare additional custom trackers to use
                                  // Defaults to empty
      }
      let engine = torrentStream(torrentLink, opts);
      engine.on('ready', () => {
        engine.files.forEach(function (file) {
            fs.exists(fullPath, (exists) => {
              if (exists) {
                let sizeOfDownloaded = fs.statSync(fullPath).size;
                let sizeInTorrent = file.length;

                if (sizeOfDownloaded === sizeInTorrent) {
                  console.log('download end:', fullPath, ' ok');
                  fs.rename(fullPath, newpath, (err) => {
                    if (err) throw err;
                    stream(req, res, newpath);
                    rimraf('../public/film/' + torrentLink + '_tmp',function (err) {
                      if (err) throw err;
                      console.log('File deleted!');
                    });
                    console.log('Rename complete!');
                  });
                }
                else
                  downloadAndStream(req, res, file, fullPath);
              }
              else {
                downloadAndStream(req, res, file, fullPath);
              }
            });
        });
      })
    }
  });
};

function downloadAndStream (req, res, file, fullPath) {
  // console.log('Download !', fullPath);

  let videoFormat = file.name.split('.').pop();
  if (videoFormat === 'mp4' || videoFormat === 'mkv' || videoFormat === 'ogg' || videoFormat === 'webm') {
    let currentStream = file.createReadStream();
    currentStream.pipe(fs.createWriteStream(fullPath));

    const pathToVideo = fullPath;
    let fileSize = file.length;
    const range = req.headers.range;
    let start = 0;
    let end = fileSize - 1;

    if (range) {
        partialContent(req, res, start, end, fileSize, file, 'false');
    } else {
        notPartialContent(req, res, fileSize, pathToVideo);
    }
  }
};

function stream (req, res, newpath) {
  const pathToVideo = newpath;
  let fileSize = fs.statSync(newpath).size;
  const range = req.headers.range;
  let start = 0;
  let end = fileSize - 1;

  if (range) {
      partialContent(req, res, start, end, fileSize, pathToVideo, 'true');

  } else {
      notPartialContent(req, res, fileSize, pathToVideo);
  }
};

function partialContent (req, res, start, end, fileSize, file, bool) {
  let range = req.headers.range;
  let parts = range.replace(/bytes=/, '').split('-');
  let newStart = parts[0];
  let newEnd = parts[1];
  start = parseInt(newStart, 10);

  if (!newEnd) {
      end = start + 100000000 >= fileSize ? fileSize - 1 : start + 100000000;
  }
  else
      end = parseInt(newEnd, 10);
  let chunksize = end - start + 1;
  let head = {
      'Content-Range': 'bytes ' + start + '-' + end + '/' + fileSize,
      'Accept-Ranges': 'bytes',
      'Content-Length': chunksize,
      'Content-Type': 'video/mp4',
      'Connection': 'keep-alive'
  };
  res.writeHead(206, head);

  if (bool == 'true') {
    var stream = fs.createReadStream(file, { start: start, end: end });
  }
  else {
    var stream = file.createReadStream({start: start, end: end});
  }

  pump(stream, res);
};

function notPartialContent (req, res, fileSize, pathToVideo) {
  const head = {
      'Content-Length': fileSize,
      'Content-Type': 'video/mp4',
  };
  fs.createReadStream(pathToVideo).pipe(res);
  res.writeHead(200, head);
};

module.exports = {
 magnetUrl:         magnetUrl,
};
