<template>
  <el-row type="flex">
    <!-- 历史提问 -->
    <el-row v-loading="historyLoading" type="flex" align="top" style="flex-flow: column;border-right: solid 1px #e6e6e6;">
      <div class="history-list">
        <el-row class="history-list-title">历史提问</el-row>
        <el-row class="history-list-container">
          <template v-for="(item, index) in historyIssueList">
            <router-link :key="index" :to="'/issue/reply/'+item.issue_id" class="el-link el-link--primary block" style="margin-top: 10px;">
              {{ index+1 }}. [{{ item.issue_id }}] [{{ item.addtime }}] {{ item.title }}
            </router-link>
            <el-tag :key="'game_in_name_'+index" type="success">{{ item.game_in_name }}</el-tag>
            <el-tag :key="'issue_type_name_'+index" type="warning">{{ item.issue_type_name }}</el-tag>
            <el-tag :key="'issue_status_'+index" type="info">{{ item.issue_status }}</el-tag>
          </template>
        </el-row>
      </div>
      <div class="note-list">
        <el-row class="note-list-title">备注</el-row>
        <div ref="noteBox" class="note-box">
          <div v-for="(item, index) in noteList" :key="index">
            <div class="note-item">
              <p class="note-item-title">{{ item.from_name }} {{ item.addtime }}</p>
              <p v-if="item.content" class="note-item-content" v-html="item.content" />
            </div>
          </div>
        </div>
        <textarea
          ref="note"
          class="note-input"
        />
        <div style="text-align: right;padding: 0 10px;">
          <el-button type="primary" @click="submitNote">提交</el-button>
        </div>
      </div>
    </el-row>
    <!-- 聊天界面 -->
    <el-row v-loading="messageLoading" style="flex: 1 1 auto;height: calc(100vh - 90px);border-right: solid 1px #e6e6e6;">
      <div ref="replyBox" class="issue-reply">
        <div v-for="(item, index) in replyList" :key="index">
          <div :class="[item.from_type === 1 ? 'issue-reply-player' : 'issue-reply-service']">
            <p class="issue-reply-title">{{ item.from_name }} {{ item.addtime }}</p>
            <p v-if="item.content" class="issue-reply-content" v-html="item.content" />
            <div v-if="item.image_url" class="block">
              <el-image :src="baseImageUrl+item.image_url" :preview-src-list="imageList" fit="cover" style="width: 150px;height: 90px;border-radius: 5px;margin-top: 10px" />
            </div>
          </div>
        </div>
      </div>
      <el-row type="flex" align="middle" class="issue-reply-tool" justify="space-between">
        <el-row type="flex" align="middle">
          <!-- 发送图片 -->
          <el-upload
            :show-file-list="false"
            :before-upload="beforeUpload"
            :http-request="uploadImage"
            :disabled="!!issueDetail.work_deal"
            action=""
            accept="image/*"
            style="margin:0 10px"
          >
            <i class="el-icon-picture" style="font-size:20px;color:#1890ff;" />
          </el-upload>
          <!-- 按键绑定 -->
          <key-map :send-type="sendType" @selectSendType="selectSendType" />
        </el-row>
      </el-row>
      <el-row class="issue-reply-textarea">
        <div
          ref="message"
          :contenteditable="!issueDetail.work_deal"
          class="issue-reply-input"
          :disabled="!!issueDetail.work_deal"
          @keydown="sendKeyPress($event)"
          @input="changeButtonState"
          @paste="handlePaste"
        />
      </el-row>
      <el-row class="issue-reply-footer">
        <el-button :disabled="sendButtonDisable" type="primary" size="small" @click="sendMessage"> 发送</el-button>
        <el-dropdown placement="top" @command="quickReply">
          <el-button :disabled="!!issueDetail.work_deal" type="primary" size="small">
            快速回复<i class="el-icon-arrow-up el-icon--right" />
          </el-button>
          <el-dropdown-menu slot="dropdown" class="quick-reply-list">
            <el-dropdown-item v-for="(item, index) in quickReplyList" :key="item.id" :command="item.content">{{ index+1 }}.{{ item.content }}</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </el-row>
    </el-row>
    <!-- 玩家资料 -->
    <el-row v-loading="issueDetailLoading">
      <div class="issue-detail">
        <p>问题编号：{{ issueDetail.issue_id }}</p>
        <p>标题：<span style="color: #F56C6C">{{ issueDetail.title }}</span></p>
        <p>问题类型：{{ issueDetail.issue_type_name }}</p>
        <p>提问玩家：{{ issueDetail.user_name }}</p>
        <p>玩家IP：{{ issueDetail.ip }}</p>
        <p>游戏名称：{{ issueDetail.game_name }}</p>
        <p>游戏内部名称：{{ issueDetail.game_in_name }}</p>
        <p style="cursor: pointer" title="点我复制" @click="copyRoleName">角色名：{{ issueDetail.role_name }}</p>
        <p>服务器：{{ issueDetail.server_id }}</p>
        <p>机型：{{ issueDetail.model }}</p>
        <p>系统版本：{{ issueDetail.systemversion }}</p>
        <p>SDK版本：{{ issueDetail.sdk_version_code }}</p>
        <p>提问时间：{{ issueDetail.addtime }}</p>
        <el-popconfirm
          title="确定问题已解决吗？"
          @onConfirm="issueDone"
        >
          <el-button slot="reference" type="primary" :disabled="!!issueDetail.work_deal">解决</el-button>
        </el-popconfirm>

      </div>
    </el-row>
  </el-row>
</template>

<script>
import { KeyMap } from './components'
import {
  fetchIssueDetail,
  fetchReplyList,
  fetchHistoryIssue,
  sendReply,
  sendImage,
  updateStatus,
  fetchQuickReplyList,
  fetchNoteList,
  sendNote
} from '@/api/issue'
import handleClipboard from '@/utils/clipboard'
import { createIssueModuleWSConnection } from '@/utils/websocket'

export default {
  name: 'IssueReply',

  components: {
    KeyMap
  },

  data() {
    return {
      wsConnector: null,
      sendType: localStorage.getItem('sendType') ? localStorage.getItem('sendType') : 'Ctrl+Enter',
      sendButtonDisable: true,
      messageLoading: false,
      historyLoading: false,
      issueDetailLoading: false,
      issueId: 0,
      replyList: [],
      imageList: [],
      baseImageUrl: '',
      baseUrl: '',
      issueDetail: {},
      historyIssueList: [],
      quickReplyList: [],
      noteList: []
    }
  },

  watch: {
    // 监听聊天内容 数据变化到底
    replyList: {
      handler: function(newValue, oldValue) {
        this.$nextTick(() => {
          this.replyBoxScrollToEnd()
        })
      },
      deep: true
    },

    // 监听聊天内容 数据变化到底
    noteList: {
      handler: function(newValue, oldValue) {
        this.$nextTick(() => {
          this.noteBoxScrollToEnd()
        })
      },
      deep: true
    }
  },

  beforeDestroy() {
    this.wsConnector.send({
      cmd: 'issue.finishReplyIssue',
      issue_id: this.issueId
    })
  },

  activated() {
    this.$nextTick(() => {
      this.replyBoxScrollToEnd()
    })
  },

  created() {
    this.wsConnector = createIssueModuleWSConnection()
    const _this = this
    this.wsConnector.onOpen = function() {
      _this.wsConnector.send({
        cmd: 'issue.readyReplyIssue',
        issue_id: _this.issueId
      })
    }
    this.wsConnector.onMessage = function(msg) {
      if (msg.code !== 0) {
        // eslint-disable-next-line no-empty
        switch (msg.code) {
        }
      } else {
        const data = msg.data

        switch (data.cmd) {
          case 'broadIssueReply':
            _this.insertNewReply(data)
            break
        }
      }
    }

    this.tempRoute = Object.assign({}, this.$route)
    this.issueId = parseInt(this.tempRoute.params.issueId)
    this.baseImageUrl = process.env.VUE_APP_BASE_IMG_URL
    this.baseUrl = process.env.VUE_APP_BASE_API
    this.getReplyList()
    this.getIssueDetail()
    this.setTagsViewTitle()
    this.getNoteList()
  },

  methods: {
    insertNewReply(data) {
      this.replyList.push({
        from_type: 2,
        from_name: data.user_name,
        content: data.content,
        addtime: data.time
      })
    },
    getReplyList() {
      const imageList = this.imageList
      const baseImageUrl = this.baseImageUrl
      this.messageLoading = true
      fetchReplyList({ issue_id: this.issueId }).then(response => {
        this.replyList = response.data.list
        this.messageLoading = false
        response.data.list.forEach(function(item) {
          item.content && (item.content = item.content.replace(/\n/g, '<br>'))
          item.image_url && imageList.push(baseImageUrl + item.image_url)
        })
      })
    },

    getIssueDetail() {
      this.issueDetailLoading = true
      fetchIssueDetail({ issue_id: this.issueId }).then(response => {
        this.issueDetail = response.data
        this.issueDetailLoading = false
        this.getHistoryIssue()
        this.getQuickReplyList()
      })
    },

    getHistoryIssue() {
      this.historyLoading = true
      fetchHistoryIssue({ user_name: this.issueDetail.user_name, issue_id: this.issueId }).then(response => {
        this.historyIssueList = response.data.list
        this.historyLoading = false
      })
    },

    setTagsViewTitle() {
      const route = Object.assign({}, this.tempRoute, { title: `${this.tempRoute.meta.title} - ${this.issueId}` })
      this.$store.dispatch('tagsView/updateVisitedView', route)
    },

    selectSendType(type) {
      this.sendType = type
    },

    sendKeyPress(event) {
      if (event.keyCode === 13) {
        event.preventDefault()
      }
      switch (this.sendType) {
        case 'Enter':
          if (event.ctrlKey && event.keyCode === 13) {
            this.newLine()
          }
          if (event.ctrlKey === false && event.keyCode === 13) {
            this.sendMessage()
          }
          break
        case 'Alt+S':
          if (!event.ctrlKey && event.keyCode === 13) {
            this.newLine()
          }
          if (event.altKey && event.keyCode === 83) {
            this.sendMessage()
          }
          break
        case 'Ctrl+Enter':
          if (!event.ctrlKey && event.keyCode === 13) {
            this.newLine()
          }
          if (event.ctrlKey && event.keyCode === 13) {
            this.sendMessage()
          }
          break
      }
    },

    newLine() {
      const message = this.$refs.message
      if (!message.lastChild || message.lastChild.nodeType === 3) {
        message.appendChild(document.createElement('br'))
      }
      message.appendChild(document.createElement('br'))
      this.setCursorToEnd()
      message.scrollTop = message.scrollHeight
    },

    sendMessage() {
      const fromName = this.$store.state.user.name
      const message = this.$refs.message
      const content = message.innerHTML.replace(/<br>/g, '\n').trim()
      // 没有内容
      if (content === '' || this.sendButtonDisable === true) {
        return
      }

      // 发送消息
      sendReply({ issue_id: this.issueId, content: content }).then(response => {
        this.replyList.push({
          from_type: 2,
          from_name: fromName,
          content: message.innerHTML.trim().replace(/\n/g, '<br>'),
          addtime: response.data.addtime
        })

        this.wsConnector.send({
          cmd: 'issue.broadIssueReply',
          issue_id: this.issueId,
          content: message.innerHTML.trim().replace(/\n/g, '<br>'),
          user_name: fromName,
          time: response.data.addtime
        })

        message.innerHTML = ''
        this.sendButtonDisable = false

        this.$store.dispatch('issue/changeIssueStatus', {
          issueId: this.issueId,
          issueStatus: '等待玩家回复',
          updateTime: response.data.addtime,
          fromName: fromName
        })
      })
    },

    changeButtonState() {
      const message = this.$refs.message
      this.sendButtonDisable = message.innerHTML.trim() === '' || message.innerHTML.trim() === '<br>'
    },

    setCursorToEnd() {
      const selection = window.getSelection()
      selection.selectAllChildren(this.$refs.message)
      selection.collapseToEnd()
    },

    beforeUpload(file) {
      const isImage = /image\/*/.test(file.type)
      const isLimit = file.size / 1024 / 1024 < 8
      if (!isImage) {
        this.$message.error('上传图片只能是图片格式!')
      }
      if (!isLimit) {
        this.$message.error('上传图片大小不能超过 8MB!')
      }
      return isImage && isLimit
    },

    uploadImage(param) {
      const fromName = this.$store.state.user.name
      const data = new FormData()
      data.append('issue_id', this.issueId)
      data.append('file', param.file)
      sendImage(data).then(response => {
        this.replyList.push({
          from_type: 2,
          from_name: fromName,
          content: '',
          image_url: response.data.image_url,
          addtime: response.data.addtime
        })

        this.imageList.push(this.baseImageUrl + response.data.image_url)

        this.$store.dispatch('issue/changeIssueStatus', {
          issueId: this.issueId,
          issueStatus: '等待玩家回复',
          updateTime: response.data.addtime,
          fromName: fromName
        })
      })
    },

    issueDone() {
      updateStatus({ issue_id: this.issueId }).then(response => {
        this.issueDetail.work_deal = true

        this.$store.dispatch('issue/changeIssueStatus', {
          issueId: this.issueId,
          issueStatus: '已处理',
          updateTime: response.data.update_time
        })
      })
    },

    replyBoxScrollToEnd() {
      const replyBox = this.$refs.replyBox
      replyBox.scrollTop = replyBox.scrollHeight
    },

    noteBoxScrollToEnd() {
      const noteBox = this.$refs.noteBox
      noteBox.scrollTop = noteBox.scrollHeight
    },

    handlePaste(event) {
      event.preventDefault()
      const text = event.clipboardData && event.clipboardData.getData('Text')
      this.insertContentOnCursor(text)
      event.target.scrollTop = event.target.scrollHeight
      this.sendButtonDisable = false
    },

    copyRoleName(event) {
      handleClipboard('【' + this.issueDetail.server_id + '】' + this.issueDetail.role_name, event)
    },

    getQuickReplyList() {
      fetchQuickReplyList({ game_id: this.issueDetail.game_id }).then(response => {
        this.quickReplyList = response.data.list
      })
    },

    quickReply(command) {
      const message = this.$refs.message
      message.append(command)
      this.setCursorToEnd()
      message.scrollTop = message.scrollHeight
      this.sendButtonDisable = false
    },

    insertContentOnCursor(content) {
      const selection = window.getSelection()
      if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0) // 获取选择范围
        range.deleteContents() // 删除选中的内容
        const el = document.createElement('div') // 创建一个空的div外壳
        el.innerHTML = content // 设置div内容为我们想要插入的内容。
        const frag = document.createDocumentFragment()// 创建一个空白的文档片段，便于之后插入dom树

        const node = el.firstChild
        const lastNode = frag.appendChild(node)
        range.insertNode(frag) // 设置选择范围的内容为插入的内容
        const contentRange = range.cloneRange() // 克隆选区
        contentRange.setStartAfter(lastNode) // 设置光标位置为插入内容的末尾
        contentRange.collapse(true) // 移动光标位置到末尾
        selection.removeAllRanges() // 移出所有选区
        selection.addRange(contentRange) // 添加修改后的选区
      }
    },

    getNoteList() {
      fetchNoteList({ issue_id: this.issueId }).then(response => {
        this.noteList = response.data.list
      })
    },

    submitNote() {
      const fromName = this.$store.state.user.name
      const note = this.$refs.note
      const noteContent = note.value.trim().replace(/\n/g, '<br>')

      sendNote({ issue_id: this.issueId, content: noteContent }).then(response => {
        this.noteList.push({
          from_name: fromName,
          content: noteContent,
          addtime: response.data.addtime
        })

        note.value = ''
      })
    }
  }
}
</script>
<style>
  .el-icon-circle-close{
    color: #ffffff;
  }
</style>
<style rel="stylesheet/scss" lang="scss" scoped>
  @import '@/styles/mixin.scss';

  .history-list {
    width: 360px;
    height: calc(50vh - 45px);

    &-title {
      font-size: 14px;
      height: 35px;
      line-height: 35px;
      padding: 0 20px;
    }

    &-container {
      padding: 0 20px;
      font-size: 14px;
      height: calc(100% - 35px);
      overflow-y: auto;
      @include scrollBar();
    }
  }

  .note {
    &-list {
      width: 360px;
      border-top: solid 1px #e6e6e6;
      height: calc(50vh - 45px);

      &-title {
        font-size: 14px;
        height: 35px;
        line-height: 35px;
        padding: 0 20px;
      }
    }

    &-box {
      height: 60%;
      padding: 0 20px;
      overflow-x: hidden;
      overflow-y: auto;
      font-size: 16px;
      @include scrollBar();
    }

    &-item{
      &-title {
        margin: 20px 0 0 0;
        color: #007eff;
      }

      &-content {
        margin: 0;
        padding: 5px;
        word-break: break-word;
      }
    }

    &-input{
      outline: none;
      resize: none;
      border-top: solid 1px #e6e6e6;
      border-left: 0;
      border-right: 0;
      border-bottom: solid 1px #e6e6e6;
      width: 100%;
      height: 20%;
      overflow-y: auto;
      @include scrollBar();
    }
  }

  .issue {
    &-reply {
      height: 60vh;
      padding: 20px;
      overflow-y: auto;
      font-size: 20px;
      @include scrollBar();

      &-player{
        margin-top: 30px;
        text-align: left;
      }

      &-title {
        margin: 0;
        color: #686868;
      }

      &-content {
        position: relative;
        margin: 5px 0 0 0;
        display: inline-block;
        background: #2ac06d;
        padding: 10px;
        border-radius: 5px;
        color: #fff;
        word-break: break-all;
      }

      &-content::before {
        display: block;
        content: '';
        position: absolute;
        top: 50%;
        left: -5px;
        margin-top: -6px;
        width:0;
        height:0;
        border-top:6px solid transparent;
        border-bottom:6px solid transparent;
        border-right:6px solid #2ac06d;
      }

      &-service{
        margin-top: 30px;
        text-align: right;

        .issue-reply-title{
          color: #686868;
        }

        .issue-reply-content{
          text-align: left;
          background: #007eff;
        }

        .issue-reply-content::before{
          left: auto;
          right: -5px;
          margin-top: -6px;
          border-top:6px solid transparent;
          border-bottom:6px solid transparent;
          border-left:6px solid #007eff;
          border-right: none;
        }

        .issue-reply-content::selection{
          background-color: #cde3ff;
        }
      }

      &-tool{
        padding: 0 10px;
        border-top: solid 1px #e6e6e6;
        border-bottom: solid 1px #e6e6e6;
      }

      &-textarea {
        height: calc(40vh - 200px);
        overflow: hidden;
      }

      &-input {
        width: 100%;
        height: calc(40vh - 200px);
        font-size: 20px;
        padding: 11px 10px;
        white-space: pre-wrap;
        word-break: break-word;
        outline: none;
        border: none;
        resize: none;
        overflow-x: hidden;
        overflow-y: auto;
        @include scrollBar();
      }

      &-footer {
        height:50px;
        text-align: right;
        padding-right: 10px;
      }
    }
  }

  .issue-detail {
    width: 360px;
    padding: 15px;
    font-size: 16px;
  }

  .quick-reply-list {
    max-width: 500px;
    max-height: 800px;
    overflow-x: hidden;
    overflow-y: auto;
    @include scrollBar();
  }

  .quick-reply-list li{
    font-size: 16px;
    margin: 10px 0;
  }
</style>
