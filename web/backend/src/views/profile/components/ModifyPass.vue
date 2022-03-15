<template>
  <el-form ref="modifyPassForm" :model="modifyPassForm" :rules="rules" class="modify-pass-form" autocomplete="off" label-position="left">
    <!--     手机号 -->
    <el-tooltip v-model="phoneTip" content="" placement="right" manual>
      <el-form-item prop="phone">
        <span class="svg-container">
          <svg-icon icon-class="phone" />
        </span>
        <el-input
          ref="phone"
          v-model="modifyPassForm.phone"
          placeholder="手机号"
          name="phone"
          type="text"
          tabindex="1"
          autocomplete="off"
          @keyup.native="checkCapslock"
          @blur="phoneTip = false"
        />
      </el-form-item>
    </el-tooltip>
    <el-tooltip v-model="passwordTip" content="Caps lock is On" placement="right" manual>
      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          :key="passwordType"
          ref="password"
          v-model="modifyPassForm.password"
          :type="passwordType"
          placeholder="新密码"
          name="password"
          tabindex="2"
          autocomplete="off"
          @keyup.native="checkCapslock"
          @blur="passwordTip = false"
        />
      </el-form-item>
    </el-tooltip>
    <!-- 验证码     -->
    <el-form-item label="验证码" prop="code">
      <el-row :span="24">
        <el-col :span="24">
          <el-input
            v-model="modifyPassForm.code"
            auto-complete="off"
            placeholder="请输入验证码"
            size=""
            maxlength="6"
            @keyup.enter.native="submit('modifyPassForm')"
          >
            <template slot="append">
              <el-button
                :disabled="showCode"
                type="primary"
                @click="getCode()"
              >{{ codeMsg }}</el-button>
            </template>

          </el-input>
        </el-col>
      </el-row>
    </el-form-item>

    <el-form-item>
      <el-button type="primary" @click="submit">确认</el-button>
    </el-form-item>
  </el-form>
</template>
<script>
import CommonLang from '@/config/lang/common'
export default {
  name: 'ModifyPass',
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: ''
        }
      }
    }
  },
  data() {
    const validatePassword = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(CommonLang.PASSWORD_EMPTY))
      }
      if (value.length < 6) {
        callback(new Error(CommonLang.PASSWORD_FORMAT_ERROR))
      } else {
        callback()
      }
    }
    const validateMobile = (rule, value, callback) => {
      if (/^1[34578]\d{9}$/.test(value) === false) {
        callback(new Error(CommonLang.PHONE_ERROR))
      } else {
        callback()
      }
    }
    return {
      modifyPassForm: {
        username: this.user.name,
        password: '',
        phone: '',
        code: ''
      },
      rules: {
        password: [{ required: true, trigger: 'blur', validator: validatePassword }],
        phone: [{ required: true, trigger: 'blur', validator: validateMobile }]
      },
      codeMsg: '获取验证码', // 获取验证码按钮文字
      // waitTime: 61, // 获取验证码按钮失效时间
      auth_time: 60, // 获取验证码按钮失效时间
      passwordType: 'password',
      passwordTip: false,
      phoneTip: false,
      loading: false,
      showDialog: false,
      redirect: undefined,
      showCode: false,
      otherQuery: {}
    }
  },
  methods: {
    checkCapslock(e) {
      const { key } = e
      this.passwordTip = key && key.length === 1 && (key >= 'A' && key <= 'Z')
    },
    submit() {
      const objThis = this
      this.$refs.modifyPassForm.validate(valid => {
        if (valid) {
          objThis.loading = true
          this.$store.dispatch('user/modifyPass', objThis.modifyPassForm)
            .then(() => {
              objThis.$emit('modifyPassword')
              this.$message({
                message: '密码修改成功，2秒后跳转重新登录',
                type: 'success'
              })
              objThis.clearTimeSet = setInterval(() => {
                objThis.$router.push(`/login?redirect=${objThis.$route.fullPath}`)
              }, 2000)
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          return false
        }
      })
    },
    /* 获取验证码*/
    getCode() {
      const objThis = this
      objThis.showCode = true
      this.$store.dispatch('user/getCode', this.modifyPassForm).then((res) => {
        if (res.code === 0) {
          this.$message({
            message: '验证码发送成功',
            type: 'success'
          })
          // 设置倒计时秒
          objThis.auth_time = 60
          const authTimeTimer = setInterval(() => {
            objThis.auth_time--
            objThis.codeMsg = objThis.auth_time + 's后重新获取'
            if (objThis.auth_time <= 0) {
              objThis.showCode = false
              objThis.codeMsg = '获取验证码'
              clearInterval(authTimeTimer)
            } else {
              objThis.showCode = true
            }
          }, 1000)
        }
      })
        .catch(() => {
          objThis.showCode = false
          objThis.codeMsg = '获取验证码'
          return false
        })
      if (this.modifyPassForm.phone === '') {
        this.showCode = true
      } else {
        this.showCode = false
      }
    }
  }
}
</script>
