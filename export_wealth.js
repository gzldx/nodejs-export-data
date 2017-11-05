'use strict';

const Promise = require('bluebird');
const _ = require('lodash');
const path = require('path');
const redis = require('redis');

let client1 = redis.createClient(6379, '127.0.0.1');
let client2 = redis.createClient(6379, '10.66.98.162');
client2.auth('dasheng123');

client1.on("error", function (err) {
    console.log("Error1 " + err);
});

client2.on("error", function (err) {
    console.log("Error2 " + err);
});

// client1.zrevrangebyscore('fans_peach_total_ranking', ['+inf', 0, 'WITHSCORES'], function(err, results) {
//     console.log(results);
//     results = results.reverse();
//     client2.zadd('fans_peach_total_ranking', results, function (err2, res) {
//         if (err2) {
//             console.error(err2);
//         }
//         console.log('success');
//     });
// });

client1.keys('anchor_fans_peach_total_ranking_*', function(err, results) {
    console.log(results);
    return Promise.each(results, (key) => {
        client1.zrevrangebyscore(key, ['+inf', 0, 'WITHSCORES'], function(err, res) {
            res = res.reverse();
            client2.zadd('anchor_fans_peach_total_ranking_'+_.replace(key, 'anchor_fans_peach_total_ranking_', ''), res);
            return 'success';
        });
    }).then(() => {
        console.log('ok');
    });
});