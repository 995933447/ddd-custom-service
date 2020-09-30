import { createConnection as createWsConnection } from 'socket.c'

import { getToken } from '@/utils/auth'

export function createIssueModuleWSConnection() {
  return createWsConnection({
    url: process.env.VUE_APP_BASE_WEBSOCKET_ADDRESS + 'issue?token=' + getToken(),
    heartBeat: ['{"cmd":"heartBeat.ping"}', 'PONG'],
    heartMaxNumber: 3,
    hearTinterval: 10000,
    sendType: 'JSON',
    messageType: 'JSON',
    restartMaxNumber: 300,
    restartTinterval: 3000
  })
}

