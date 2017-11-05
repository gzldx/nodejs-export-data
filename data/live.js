'use strict';

const _ = require('lodash');
const Promise = require('bluebird');
const avModel = require('../library/AVModel');
const Model = require('../library/Model');
const moment = require('moment');

function init(){
    return avModel.queryAll('AGVideo').then((results) => {
        return importVideoVariable(results).then(() => {
            return importVideo(results);
        }).then(() => {
            return importVideoSubtitle();
        });
    });
}

function importVideoVariable(data) {
    let jsonData = {};
    _.each(data, (video) => {
        jsonData[video.id] = video.get('streamId');
    });
    global.shareData.videos = jsonData;
    return Promise.resolve(jsonData);
}

function importVideo(data) {
    let liveType = {
        normal: 1,
        pk: 2,
        onlineGame: 3
    };
    let db = Model.connect('db_live');
    return Promise.each(data, (video) => {
        let uid = global.shareData.users[video.get('owner').id];
        if (!uid) {
            return;
        }
        if (video.get('status') === 2) {
            return;
        }
        let data = {
            uid: uid,
            stream_id: video.get('streamId'),
            chatroom_id: video.get('conversationId'),
            live_type: liveType[video.get('liveType')] ? liveType[video.get('liveType')] : 0 ,
            title: video.get('title') ? new Buffer(video.get('title')).toString('base64') : '',
            cover: video.get('cover') ? video.get('cover').url() : '',
            rtmp_push_url: video.get('rtmpLiveUrl') || '',
            flv_play_url: _.replace(video.get('hdlLiveUrl') || '', 'http:', 'https:'),
            hls_play_url: _.replace(video.get('hlsLiveUrl') || '', 'http:', 'https:'),
            rtmp_play_url: video.get('rtmpLiveUrl') || '',
            hls_playback_url: _.replace(video.get('hlsPlaybackUrl') || '', 'http:', 'https:'),
            hls_playback_url_mp4: _.replace(video.get('videoFileUrl') || '', 'http:', 'https:'),
            playback_status: video.get('hlsPlaybackUrl') ? 1 : 0,
            device_type: video.get('platform') || '',
            ip: video.get('ip') || '',
            status: convertVideoStatus(video.get('status')),
            orientation: video.get('orientation') === 'portrait' ? 2 : 1,
            platform: _getPlatform(video),
            real_viewer_num: video.get('realTimeViewerNum'),
            total_viewer_num: video.get('totalViewersNum'),
            realtime_viewer_num: 0,
            duration: video.get('duration'),
            priority: 1000,
            create_time: moment(video.createdAt).format('X'),
            update_time: moment(video.updatedAt).format('X'),
            deleted: video.get('deleted')
        };
        return Model.insert(db, 't_live_summary', data).then(() => {
            return Model.insert(db, 't_live_'+ _.padStart(uid % 100, 2, 0), data);
        });
    }).then(() => {
        console.log('AGVideo导入成功');
        return 'AGVideo导入成功';
    });
}

function importVideoSubtitle() {
    return avModel.queryAll('AGVideoLiveMessage').then((records) => {
        let db = Model.connect('db_live');
        return Promise.each(records, (record) => {
            let streamId = global.shareData.videos[record.get('videoId')];
            if (!streamId) {
                return true;
            }
            return Model.insert(db, 't_live_subtitle_file', {
                id: null,
                index: record.get('index'),
                stream_id: streamId,
                file_url: record.get('file').url(),
                json_file_url: record.get('file').url(),
                create_time: moment(record.createdAt).format('X'),
                update_time: moment(record.updatedAt).format('X'),
                deleted: record.get('deleted')
            });
        }).then(() => {
            console.log('AGVideoLiveMessage导入成功');
            return 'AGVideoLiveMessage导入成功';
        });
    });
}

//1:ios, 2:android, 3:pc, 4:obs 默认为0'
function _getPlatform(video)
{
    let platform = 0;
    if (video.get('osType')) {
        platform = video.get('osType') === 'iOS' ? 1 : 2;
    } else if (video.get('OBS')) {
        platform = 4;
    }
    return platform;
}

// videoStatus: {
//     init: 1, // 初始状态
//     live: 2, //直播
//     interrupt: 3, //中断
//     playback: 4, //回放
//     end: 5, //结束 - 正常视频都会自动变为回放状态，出现此状态表明结束直播途中发生了异常
//     forbidden: 9, //视频被禁止
//     discard: 10, //丢弃 - 视频本身有问题无法生成回放，比如流内容为空
//     noplayback: 14,   //视频正常结束，不生成回放
//     mask: 104 //屏蔽
// }
function convertVideoStatus(status) {
    let statusArr = {
        1: 0,
        2: 1,
        3: 2,
        4: 3,
        5: 3,
        9: 4,
        10: 6,
        14: 6,
        104: 5
    }
    return statusArr[status];
}

module.exports = {
    run: () => {
        return init();
    }
};