<template>
  <el-popover
    v-model="sendTypeVisible"
    placement="top-start"
    width="200">
    <p style="cursor:pointer" @click="switchSendType('Enter')">
      按Enter键发送消息
      <i v-show="sendType === 'Enter'" class="el-icon-check" style="float:right;font-size:16px;"/>
    </p>
    <p style="cursor:pointer" @click="switchSendType('Alt+S')">
      按Alt+S键发送消息
      <i v-show="sendType === 'Alt+S'" class="el-icon-check" style="float:right"/>
    </p>
    <p style="cursor:pointer" @click="switchSendType('Ctrl+Enter')">
      按Ctrl+Enter键发送消息
      <i v-show="sendType === 'Ctrl+Enter'" class="el-icon-check" style="float:right"/>
    </p>
    <el-button slot="reference" style="padding-right:5px" type="text"><i class="el-icon-s-tools" style="font-size: 20px;" /></el-button>
  </el-popover>
</template>

<script>
export default {
  name: 'KeyMap',
  props: {
    sendType: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      sendTypeVisible: false
    }
  },
  created() {
  },
  methods: {
    switchSendType(type) {
      localStorage.setItem('sendType', type)
      this.sendTypeVisible = false
      this.$emit('selectSendType', type)
    }
  }
}
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
@import "@/styles/mixin.scss";
</style>
