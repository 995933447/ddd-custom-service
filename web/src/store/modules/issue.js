import it from "element-ui/src/locale/lang/it";
import da from "element-ui/src/locale/lang/da";

const state = {
  list: []
}

const mutations = {
  SET_STATE: (state, { key, value }) => {
    if (state.hasOwnProperty(key)) {
      state[key] = value
    }
  },

  CHANGE_ISSUE_STATUS: (state, { issueId, issueStatus, updateTime, fromName }) => {
    state.list.forEach(function(item) {
      if (item.issue_id === issueId) {
        item.issue_status = issueStatus
        item.updatetime = updateTime
        item.last_response = fromName
      }
    })
  },

  UPDATE_ISSUE_NEWEST_REPLIER: (state, { issueId, replierName }) => {
    console.log(2333)
    state.list.forEach(function(item, index) {
      if (issueId === item.issue_id) {
        item.replying = replierName
        state.list.splice(index, 1, item)
      }
    })
  }
}

const actions = {
  setState({ commit }, data) {
    commit('SET_STATE', data)
  },

  changeIssueStatus({ commit }, data) {
    commit('CHANGE_ISSUE_STATUS', data)
  },

  updateWhoReplyingIssue({ commit }, data) {
    commit('UPDATE_ISSUE_NEWEST_REPLIER', data)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}

