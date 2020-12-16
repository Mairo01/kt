<template>
  <div>
    <div v-show="data.stripe.enabled">
      <el-row :gutter="24" class="zero-margin-bottom">
        <el-col :span="11">
          <el-form-item :label="$root.labels.name + ':'">
          </el-form-item>
        </el-col>
        <el-col :span="11">
          <label class="el-form-item__label">
          {{$root.labels.value + ': '}}
          <el-tooltip placement="top">
            <div slot="content">{{ $root.labels.metadata_value_tooltip }}</div>
            <i class="el-icon-question am-tooltip-icon"></i>
          </el-tooltip>
          </label>
        </el-col>
      </el-row>
      <el-row :gutter="24" type="flex" v-for="(pair, index) in stripeMetaData" :key="index" class="small-margin-bottom am-payments-meta-data">
        <el-col :span="10">
            <el-input type="text" :name="pair.name" v-model="stripeMetaData[index].key"/>
        </el-col>
        <el-col :span="10">
            <el-input type="text" v-model="stripeMetaData[index].value"/>
        </el-col>
        <el-col :span="4">
          <span @click="deletePair(index)">
            <img class="svg" width="16px" :src="$root.getUrl+'public/img/delete.svg'">
          </span>
        </el-col>
      </el-row>
      <el-row :gutter="24">
        <el-col>
          <el-button type="primary" v-on:click="addPair()">{{$root.labels.add_metaData}}</el-button>
        </el-col>
      </el-row>
    </div>
    <el-form-item :label="$root.labels.description_wc + ':'" v-show="data.wc.enabled">
      <el-input
          type="textarea"
          :autosize="{ minRows: 4, maxRows: 6}"
          v-model="description_wc"
      >
      </el-input>
    </el-form-item>
    <el-form-item :label="$root.labels.description_paypal + ':'" v-show="data.payPal.enabled">
      <el-input
          type="textarea"
          :autosize="{ minRows: 4, maxRows: 6}"
          v-model="description_paypal"
      >
      </el-input>
    </el-form-item>
    <el-form-item :label="$root.labels.description_stripe + ':'" v-show="data.stripe.enabled">
      <el-input
          type="textarea"
          :autosize="{ minRows: 4, maxRows: 6}"
          v-model="description_stripe"
      >
      </el-input>
    </el-form-item>
  </div>
</template>

<script>
export default {
  name: 'PaymentsMetaData',
  props: {
    data: Object,
    tab: String
  },
  data () {
    return {
      stripeMetaData: null
    }
  },
  mounted () {
    this.stripeMetaData = Object.entries(this.metaData).map(([key, value]) => ({ key, value }))
    this.stripeMetaData.push({key: '', value: ''})
  },
  computed: {
    description_wc: {
      get () {
        return this.tab === 'appointments' ? this.data.wc.checkoutData.appointment : this.data.wc.checkoutData.event
      },
      set (newDescription) {
        if (this.tab === 'appointments') {
          this.data.wc.checkoutData.appointment = newDescription
        } else {
          this.data.wc.checkoutData.event = newDescription
        }
      }
    },
    description_paypal: {
      get () {
        return this.tab === 'appointments' ? this.data.payPal.description.appointment : this.data.payPal.description.event
      },
      set (newDescription) {
        if (this.tab === 'appointments') {
          this.data.payPal.description.appointment = newDescription
        } else {
          this.data.payPal.description.event = newDescription
        }
      }
    },
    description_stripe: {
      get () {
        return this.tab === 'appointments' ? this.data.stripe.description.appointment : this.data.stripe.description.event
      },
      set (newDescription) {
        if (this.tab === 'appointments') {
          this.data.stripe.description.appointment = newDescription
        } else {
          this.data.stripe.description.event = newDescription
        }
      }
    },
    metaData: {
      get () {
        if (this.tab === 'appointments') {
          return this.data.stripe.metaData.appointment != null ? this.data.stripe.metaData.appointment : {}
        } else {
          return this.data.stripe.metaData.event != null ? this.data.stripe.metaData.event : {}
        }
      }
    }
  },
  methods: {
    addPair () {
      this.stripeMetaData.push({key: '', value: ''})
    },
    deletePair (index) {
      this.stripeMetaData.splice(index, 1)
    }
  }
}
</script>
