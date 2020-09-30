<template>
  <div class="app-container">
    <div class="filter-container">
      <el-cascader
        v-loading="gameListLoading"
        :options="gameList"
        :show-all-levels="false"
        :props="{ expandTrigger: 'hover', multiple: true }"
        collapse-tags
        clearable
        class="filter-item"
        style="width: 300px;"
        size="small"
        @change="handleGameListChange"
      />
      <el-select v-model="listQuery.issue_type_id" placeholder="类型" style="width: 100px;" clearable class="filter-item" size="small">
        <el-option v-for="item in typeList" :key="item.type_id" :label="item.type_name" :value="item.type_id" />
      </el-select>
      <el-select v-model="listQuery.issue_status" placeholder="处理进度" style="width: 150px;" clearable class="filter-item" size="small">
        <el-option label="全部" value="1" />
        <el-option label="未回复" value="2" />
        <el-option label="等待玩家回复" value="3" />
        <el-option label="等待客服回复" value="4" />
        <el-option label="已处理" value="5" />
      </el-select>
      <el-input v-model="listQuery.issue_id" placeholder="问题编号" style="width: 150px;" class="filter-item" size="small" @keyup.enter.native="handleSearch" />
      <el-input v-model="listQuery.user_name" placeholder="账号" style="width: 150px;" class="filter-item" size="small" @keyup.enter.native="handleSearch" />
      <el-input v-model="listQuery.role_name" placeholder="角色名" style="width: 150px;" class="filter-item" size="small" @keyup.enter.native="handleSearch" />
      <el-input v-model="listQuery.service_name" placeholder="客服名" style="width: 150px;" class="filter-item" size="small" @keyup.enter.native="handleSearch" />
      <el-date-picker
        v-model="listQuery.date_range"
        type="daterange"
        start-placeholder="开始日期"
        end-placeholder="结束日期"
        :picker-options="datePickerOptions"
        :default-time="['00:00:00', '23:59:59']"
        value-format="yyyy-MM-dd HH:mm:ss"
        size="small"
      />
      <el-switch
        v-model="listQuery.is_star"
        active-color="#ffb23f"
        active-text="星标"
        inactive-text="全部"
        @change="handleSearch"
      />
      <el-button type="primary" icon="el-icon-search" class="filter-item" @click="handleSearch">
        搜索
      </el-button>
      <!--<el-button :loading="exportLoading" type="primary" icon="el-icon-download" class="filter-item" @click="handleExport">
        导出
      </el-button>-->
      <el-button type="primary" icon="el-icon-chat-dot-round" class="filter-item" @click="batchReplyVisible = true">
        批量回复
      </el-button>
    </div>

    <el-table v-loading="listLoading" :data="list" :row-class-name="tableRowClassName" height="73vh" border fit highlight-current-row style="width: 100%" @selection-change="issueSelectionChange">
      <el-table-column
        type="selection"
        width="40"
      />
      <el-table-column align="center" label="星标" width="60px">
        <template slot-scope="{row}">
          <i :class="[row.is_star ? 'el-icon-star-on' : 'el-icon-star-off']" style="cursor: pointer;color: #ffb23f;font-size: 16px" @click="changeIsStar(row)" />
        </template>
      </el-table-column>
      <el-table-column align="center" label="序号" width="60px">
        <template slot-scope="scope">
          {{ scope.$index+1 }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="问题编号" prop="issue_id" />
      <el-table-column align="center" label="玩家账号" prop="user_name" />
      <el-table-column align="center" label="所属游戏" prop="game_name" />
      <el-table-column align="center" label="内部名称" prop="game_in_name" />
      <el-table-column align="center" label="角色名称" prop="role_name" />
      <el-table-column align="center" label="问题类型">
        <template slot-scope="{row}">
          <el-tag :type="row.issue_type_name | issueTypeFilter">
            {{ row.issue_type_name }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" label="标题">
        <template slot-scope="{row}">
          <el-link :href="'#/issue/reply/'+row.issue_id">
            {{ row.title }}
          </el-link>
        </template>
      </el-table-column>
      <el-table-column align="center" label="提问时间" prop="addtime" width="160" />
      <el-table-column align="center" label="问题状态">
        <template slot-scope="{row}">
          <el-tag :type="row.issue_status | issueStatusFilter">
            {{ row.issue_status }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" label="更新时间" prop="updatetime" width="160" />
      <el-table-column align="center" label="最后回复人" prop="last_response" />
      <el-table-column align="center" label="当前处理人员" prop="replying" />
      <el-table-column align="center" label="操作" width="120">
        <template slot-scope="scope">
          <router-link :to="'/issue/reply/'+scope.row.issue_id">
            <el-button type="primary" size="mini" icon="el-icon-chat-dot-round">回复</el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="批量回复" :visible.sync="batchReplyVisible">
      <textarea ref="batchReplyMessage" style="width: 100%;height: 200px;border:1px solid #dfe6ec;resize: none;outline: none;" />
      <div slot="footer" class="dialog-footer">
        <el-switch
          v-model="batchReplyCloseIssue"
          active-text="关单"
          inactive-text="不关单"
        />
        <el-button @click="batchReplyVisible = false">取 消</el-button>
        <el-button type="primary" @click="handleBatchReply">回 复</el-button>
      </div>
    </el-dialog>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import { fetchList, fetchTypeList, sendBatchReply, sendIsStar } from '@/api/issue'
import { fetchGameList } from '@/api/game'
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import { createIssueModuleWSConnection } from '@/utils/websocket'

export default {
  name: 'IssueList',
  components: { Pagination },
  filters: {
    issueTypeFilter(type) {
      const filterMap = {
        '遊戲BUG': 'success',
        '登入相關': 'danger',
        '活動問題': 'info',
        '其他': 'info',
        '建議': 'info'
      }
      return filterMap[type]
    },
    issueStatusFilter(status) {
      const filterMap = {
        '等待客服回复': 'danger',
        '未回复': 'danger',
        '等待玩家回复': 'info',
        '已处理': 'success'
      }
      return filterMap[status]
    }
  },
  data() {
    return {
      wsConnector: null,
      total: 0,
      listLoading: true,
      listQuery: {
        game_id_list: []
      },
      selectedIssueList: [],
      gameList: [],
      gameListLoading: false,
      typeList: [],
      typeListLoading: false,
      exportLoading: false,
      batchReplyVisible: false,
      batchReplyCloseIssue: false,
      datePickerOptions: {
        shortcuts: [{
          text: '今天',
          onClick(picker) {
            const start = new Date(parseTime(new Date(), '{y}-{m}-{d} 00:00:00'))
            const end = new Date(parseTime(new Date(), '{y}-{m}-{d} 23:59:59'))
            picker.$emit('pick', [start, end])
          }
        },
        {
          text: '昨天',
          onClick(picker) {
            let start = new Date()
            let end = new Date()
            start.setTime(start.getTime() - 86400000)
            end.setTime(end.getTime() - 86400000)
            start = new Date(parseTime(start, '{y}-{m}-{d} 00:00:00'))
            end = new Date(parseTime(end, '{y}-{m}-{d} 23:59:59'))
            picker.$emit('pick', [start, end])
          }
        },
        {
          text: '最近一周',
          onClick(picker) {
            let start = new Date()
            let end = new Date()
            start.setTime(start.getTime() - 86400000 * 7)
            start = new Date(parseTime(start, '{y}-{m}-{d} 00:00:00'))
            end = new Date(parseTime(end, '{y}-{m}-{d} 23:59:59'))
            picker.$emit('pick', [start, end])
          }
        },
        {
          text: '最近一个月',
          onClick(picker) {
            let start = new Date()
            let end = new Date()
            start.setTime(start.getTime() - 86400000 * 30)
            start = new Date(parseTime(start, '{y}-{m}-{d} 00:00:00'))
            end = new Date(parseTime(end, '{y}-{m}-{d} 23:59:59'))
            picker.$emit('pick', [start, end])
          }
        },
        {
          text: '最近三个月',
          onClick(picker) {
            let start = new Date()
            let end = new Date()
            start.setTime(start.getTime() - 86400000 * 90)
            start = new Date(parseTime(start, '{y}-{m}-{d} 00:00:00'))
            end = new Date(parseTime(end, '{y}-{m}-{d} 23:59:59'))
            picker.$emit('pick', [start, end])
          }
        }]
      }
    }
  },
  computed: {
    list: {
      get() {
        return this.$store.state.issue.list
      },
      set(value) {
        this.$store.dispatch('issue/setState', {
          key: 'list',
          value: value
        })
      }
    }
  },
  created() {
    this.wsConnector = createIssueModuleWSConnection()
    const _this = this

    this.wsConnector.onMessage = function(msg) {
      if (msg.code !== 0) {
        // eslint-disable-next-line no-empty
        switch (msg.code) {
        }
      } else {
        const data = msg.data

        switch (data.cmd) {
          case 'userReadyReply':
            _this.$store.dispatch('issue/updateWhoReplyingIssue', {
              issueId: data.issue_id,
              replierName: data.user_name
            })
            break
          case 'updateWhoReplyIssues':
            setTimeout(function() {
              console.log('Pull replier.')
              _this.fetchRepliersOfCurrentIssueList()
            }, 1000)
            break
        }
      }
    }

    this.wsConnector.onOpen = function() {
      _this.getList()
    }

    this.getGameList()
    this.getTypeList()
  },
  methods: {
    fetchRepliersOfCurrentIssueList() {
      const issueIds = []

      const _this = this
      this.list.forEach(function(item, index) {
        item.replying = ''
        _this.$set(_this.list, index, item)
        issueIds.push(item.issue_id)
      })

      this.wsConnector.send({
        cmd: 'issue.fetchWhoCurrentReplyIssue',
        issue_ids: issueIds
      })
    },

    getList() {
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.list
        this.total = response.data.total
        this.listLoading = false
        this.fetchRepliersOfCurrentIssueList()
      })
    },
    getGameList() {
      this.gameListLoading = true
      fetchGameList().then(response => {
        this.gameList = response.data.list
        this.gameListLoading = false
      })
    },

    getTypeList() {
      this.typeListLoading = true
      fetchTypeList().then(response => {
        this.typeList = response.data.list
        this.typeListLoading = false
      })
    },

    handleSearch() {
      this.listQuery.page = 1
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.list
        this.total = response.data.total
        this.listLoading = false
        this.fetchRepliersOfCurrentIssueList()
      })
    },

    handleExport() {
      console.log()
    },

    handleGameListChange(value) {
      const listQuery = this.listQuery
      this.listQuery.game_id_list = []
      value.map(function(item) {
        listQuery.game_id_list.push(item[1])
      })
    },

    tableRowClassName({ row, rowIndex }) {
      if (rowIndex % 2 === 0) {
        return 'stripe-row'
      }
      return ''
    },

    issueSelectionChange(value) {
      this.selectedIssueList = []
      for (const item of value) {
        this.selectedIssueList.push(item.issue_id)
      }
    },

    handleBatchReply() {
      const fromName = this.$store.state.user.name
      const message = this.$refs.batchReplyMessage
      if (this.selectedIssueList.length === 0) {
        this.$message.error('请选中要回复的工单')
      }
      sendBatchReply({
        issue_id_list: this.selectedIssueList,
        content: message.value,
        work_deal: this.batchReplyCloseIssue
      }).then(response => {
        const issueStatus = this.batchReplyCloseIssue ? '已处理' : '等待玩家回复'
        for (const issueId of this.selectedIssueList) {
          this.$store.dispatch('issue/changeIssueStatus', {
            issueId: issueId,
            issueStatus: issueStatus,
            updateTime: response.data.addtime,
            fromName: fromName
          })

          this.wsConnector.send({
            cmd: 'issue.broadIssueReply',
            issue_id: issueId,
            content: message.value,
            user_name: fromName,
            time: response.data.addtime
          })
        }

        message.value = ''
      })
    },

    changeIsStar(row) {
      const issue_id = row.issue_id
      const is_star = row.is_star ? 0 : 1
      row.is_star = is_star
      sendIsStar({
        issue_id: issue_id,
        is_star: is_star
      }).then(response => {

      })
    }
  }
}
</script>
<style>
  .el-table--enable-row-hover .el-table__body tr:hover>td {
    background-color: #c4fdff;
  }
  .el-table__body tr.current-row>td {
    background-color: #c4fdff;
  }
  .stripe-row td{
    background-color: #eafdff;
  }
  .el-table td {
    padding: 0;
  }
  .el-table .cell {
    word-break: normal;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    height: 26px;
    line-height: 26px;
  }
  .el-table .el-button--mini {
    padding: 4px 4px;
  }
  .el-table .el-tag {
    background: none;
    border: 0;
  }
  .el-cascader-menu__wrap {
    height: 235px;
  }
  .el-button+.el-button{
    margin-left:0px
  }
</style>
