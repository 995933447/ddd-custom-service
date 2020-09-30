import request from '@/utils/request'

export function fetchList(data) {
  return request({
    url: '/issue/list',
    method: 'post',
    data: data
  })
}

export function fetchTypeList(query) {
  return request({
    url: '/issue/typeList',
    method: 'get',
    params: query
  })
}

export function fetchIssueDetail(query) {
  return request({
    url: '/issue/detail',
    method: 'get',
    params: query
  })
}

export function fetchHistoryIssue(query) {
  return request({
    url: '/issue/history',
    method: 'get',
    params: query
  })
}

export function fetchReplyList(query) {
  return request({
    url: '/issue/replyList',
    method: 'get',
    params: query
  })
}

export function sendReply(data) {
  return request({
    url: '/issue/reply',
    method: 'post',
    data: data
  })
}

export function sendImage(data) {
  return request({
    url: '/issue/uploadImage',
    method: 'post',
    data: data
  })
}

export function updateStatus(data) {
  return request({
    url: '/issue/updateStatus',
    method: 'post',
    data: data
  })
}

export function fetchQuickReplyList(query) {
  return request({
    url: '/issue/quickReplyList',
    method: 'get',
    params: query
  })
}

export function fetchNoteList(query) {
  return request({
    url: '/issue/noteList',
    method: 'get',
    params: query
  })
}

export function sendNote(data) {
  return request({
    url: '/issue/note',
    method: 'post',
    data: data
  })
}

export function sendBatchReply(data) {
  return request({
    url: '/issue/batchReply',
    method: 'post',
    data: data
  })
}

export function sendIsStar(data) {
  return request({
    url: '/issue/isStar',
    method: 'post',
    data: data
  })
}
