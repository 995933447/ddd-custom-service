import request from '@/utils/request'

export function fetchGameList(query) {
  return request({
    url: '/game/list',
    method: 'get',
    params: query
  })
}
