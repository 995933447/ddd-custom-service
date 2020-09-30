<template>
  <el-popover
    placement="top-start"
    width="364">
    <table class="chat-tool-table">
      <tr>
        <th><div style="margin-bottom:8px">表情</div></th>
      </tr>
      <tr v-for="(num, index) in emojiCount" :key="index">
        <td v-for="(emoji, emojiIndex) in emojis" v-if="(emojiIndex >= num) && (emojiIndex < ((10 * index) + 10))" :key="emoji.name">
          <img :src="emoji.src" :alt="emoji.name" @click="selectEmoji(emoji.name)">
        </td>
      </tr>
    </table>
    <el-button slot="reference" type="text"><svg-icon icon-class="emoji" style="font-size:14px;"/></el-button>
  </el-popover>
</template>

<script>

export default {
  name: 'Expression',
  data() {
    return {
      emojiCount: [
        0
      ],
      emojis: [{
        name: '1F600',
        src: '/static/images/emoji/1F600.png'
      }, {
        name: '1F601',
        src: '/static/images/emoji/1F601.png'
      }, {
        name: '1F603',
        src: '/static/images/emoji/1F603.png'
      }, {
        name: '1F606',
        src: '/static/images/emoji/1F606.png'
      }, {
        name: '1F602',
        src: '/static/images/emoji/1F602.png'
      }, {
        name: '1F618',
        src: '/static/images/emoji/1F618.png'
      }, {
        name: '1F619',
        src: '/static/images/emoji/1F619.png'
      }, {
        name: '1F617',
        src: '/static/images/emoji/1F617.png'
      }, {
        name: '1F624',
        src: '/static/images/emoji/1F624.png'
      }, {
        name: '1F625',
        src: '/static/images/emoji/1F625.png'
      }, {
        name: '1F631',
        src: '/static/images/emoji/1F631.png'
      }, {
        name: '1F622',
        src: '/static/images/emoji/1F622.png'
      }, {
        name: '1F612',
        src: '/static/images/emoji/1F612.png'
      }, {
        name: '1F616',
        src: '/static/images/emoji/1F616.png'
      }, {
        name: '1F609',
        src: '/static/images/emoji/1F609.png'
      }, {
        name: '1F628',
        src: '/static/images/emoji/1F628.png'
      }, {
        name: '1F637',
        src: '/static/images/emoji/1F637.png'
      }, {
        name: '1F644',
        src: '/static/images/emoji/1F644.png'
      }, {
        name: '1F923',
        src: '/static/images/emoji/1F923.png'
      }, {
        name: '1F975',
        src: '/static/images/emoji/1F975.png'
      }, {
        name: '2639',
        src: '/static/images/emoji/2639.png'
      }, {
        name: '263A',
        src: '/static/images/emoji/263A.png'
      }]
    }
  },
  created() {
    for (let i = 0; i < this.emojis.length / 10; i++) {
      this.emojiCount.push((10 * i) + 10)
    }
  },
  methods: {
    selectEmoji(emojiCode) {
      this.$emit('selectExpression', emojiCode)
    }
  }
}
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
.chat-tool-table {
  img {
    cursor: pointer;
    width:26px;
    margin: 0 4px 4px 0;
  }
}
</style>
