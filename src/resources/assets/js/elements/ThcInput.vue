<template>
  <div class="field">
    <div class="control thc-input"
         :class="{
                'is-filed': localValue.length,
                'is-active': isActive,
                'has-icons-right': type === 'password'
            }">
      <template v-if="type !== 'date'">
        <template v-if="!multiline">
          <input
              :title="label"
              @blur="isActive = false"
              @focus="isActive = true"
              v-model="localValue"
              :type="showPassword ? 'text' : type"
              :id="name"
              :name="name"
              class="input"
              :class="errorMessage.length ? 'is-danger' + inputClass : inputClass"
              :required="isRequired">
        </template>
        <template v-else>
          <textarea-autosize
              rows="1"
              :title="label"
              @blur="isActive = false"
              @focus="isActive = true"
              v-model="localValue"
              :type="showPassword ? 'text' : type"
              :id="name"
              :name="name"
              class="textarea"
              :class="errorMessage.length ? 'is-danger' + inputClass : inputClass"
              :required="isRequired"></textarea-autosize>
        </template>
      </template>
      <template v-else>
        <flat-pickr
            class="input"
            :class="errorMessage.length ? 'is-danger' + inputClass : inputClass"
            :required="isRequired"
            @blur="isActive = false"
            @focus="isActive = true"
            :config="config"
            v-model="localValue">
        </flat-pickr>
      </template>
      <label :for="name" v-if="label">
        {{ label }}
      </label>

      <span class="icon is-right" v-if="type === 'password'" @click="showPassword = !showPassword">
                <i class="fas" :class="{'fa-eye': !showPassword, 'fa-eye-slash': showPassword}"></i>
            </span>
      <p v-if="errorMessage.length" class="has-text-danger error">{{ errorMessage }}</p>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'ThcInput',
    props: {
      value: {
        required: false,
        default: ''
      },
      name: {
        required: true,
        type: String
      },
      label: {
        required: false
      },
      type: {
        required: false,
        type: String,
        default: 'text'
      },
      inputClass: {
        required: false,
        type: String,
        default: ''
      },
      isRequired: {
        required: false,
        type: Boolean,
        default: false
      },
      error: {
        required: false,
        type: String,
        default: ''
      },
      multiline: {
        required: false,
        type: Boolean,
        default: false
      }
    },
    computed: {
      errorMessage() {
        if (typeof this.error === "object")
          return this.error[0]
        return this.error
      },
      localValue: {
        get() {
          return this.value
        },
        set(value) {
          this.$emit('input', value)
        }
      }
    },
    data() {
      return {
        isActive: false,
        showPassword: false,
        config: {
          altFormat: 'd/m/Y',
          altInput: true,
          maxDate: 'today'
        }
      }
    }
  }
</script>

<style scoped>
  .control.has-icons-right .icon {
    height: 48px;
    line-height: 48px;
    cursor: pointer;
    pointer-events: painted;
  }

  .error {
    font-size: 12px;
    line-height: 24px;
    padding: 4px 0;
  }
</style>