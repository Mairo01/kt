<template>
  <div>
    <div class="am-dialog-scrollable">

      <!-- Dialog Header -->
      <div class="am-dialog-header">
        <el-row>
          <el-col :span="14">
            <h2>{{ $root.labels[type + '_placeholders'] }}</h2>
          </el-col>
          <el-col :span="10" class="align-right">
            <el-button @click="closeDialog" class="am-dialog-close" size="small" icon="el-icon-close"></el-button>
          </el-col>
        </el-row>
      </div>

      <!-- Placeholders -->
      <div class="am-email-codes">
        <div class="am-email-code">
          <el-tooltip
              v-for="code in sortedPlaceholders"
              :key="code.code"
              effect="dark"
              :content="code.label"
              placement="left">
            <p @click="copyCodeText(('parse' in code) ? getParsedCodeLabel(code) : code.value)" :class="{'am-lite-container-disabled' : isDisabledInLite(code.value)}">
              <i class="el-icon-information"></i>
              <span>{{ code.value }}</span>
              <span class="am-copy-code">{{ $root.labels.copy }}</span>
              <span class="am-copied-code">{{ $root.labels.copied }}</span>
            </p>
          </el-tooltip>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
  import imageMixin from '../../../../js/common/mixins/imageMixin'
  import priceMixin from '../../../../js/common/mixins/priceMixin'

  export default {
    mixins: [imageMixin, priceMixin],

    props: {
      entity: {
        default: 'appointment',
        type: String
      },
      customFields: {
        default: []
      },
      categories: {
        default: []
      },
      coupons: {
        default: []
      },
      type: {
        default: 'email',
        type: String
      },
      userTypeTab: null,
      linksForParsing: {}
    },

    data () {
      return {
        commonPlaceholders: [
          {
            value: '%reservation_name%',
            label: this.$root.labels.ph_reservation_name
          },
          {
            value: '%reservation_description%',
            label: this.$root.labels.ph_reservation_description
          },
          {
            value: '%company_address%',
            label: this.$root.labels.ph_company_address
          },
          {
            value: '%company_name%',
            label: this.$root.labels.ph_company_name
          },
          {
            value: '%company_phone%',
            label: this.$root.labels.ph_company_phone
          },
          {
            value: '%company_website%',
            label: this.$root.labels.ph_company_website
          },
          {
            value: '%customer_email%',
            label: this.$root.labels.ph_customer_email
          },
          {
            value: '%customer_first_name%',
            label: this.$root.labels.ph_customer_first_name
          },
          {
            value: '%customer_last_name%',
            label: this.$root.labels.ph_customer_last_name
          },
          {
            value: '%customer_full_name%',
            label: this.$root.labels.ph_customer_full_name
          },
          {
            value: '%customer_phone%',
            label: this.$root.labels.ph_customer_phone
          },
          {
            value: '%customer_note%',
            label: this.$root.labels.ph_customer_note
          },
          {
            value: '%location_address%',
            label: this.$root.labels.ph_location_address
          },
          {
            value: '%location_description%',
            label: this.$root.labels.ph_location_description
          },
          {
            value: '%location_name%',
            label: this.$root.labels.ph_location_name
          },
          {
            value: '%location_phone%',
            label: this.$root.labels.ph_location_phone
          }
        ],

        appointmentPlaceholders: [
          {
            value: '%employee_email%',
            label: this.$root.labels.ph_employee_email
          },
          {
            value: '%employee_first_name%',
            label: this.$root.labels.ph_employee_first_name
          },
          {
            value: '%employee_last_name%',
            label: this.$root.labels.ph_employee_last_name
          },
          {
            value: '%employee_full_name%',
            label: this.$root.labels.ph_employee_full_name
          },
          {
            value: '%employee_phone%',
            label: this.$root.labels.ph_employee_phone
          },
          {
            value: '%employee_note%',
            label: this.$root.labels.ph_employee_note
          },
          {
            value: '%appointment_status%',
            label: this.$root.labels.ph_appointment_status
          },
          {
            value: '%appointment_date%',
            label: this.$root.labels.ph_appointment_date
          },
          {
            value: '%appointment_date_time%',
            label: this.$root.labels.ph_appointment_date_time
          },
          {
            value: '%appointment_duration%',
            label: this.$root.labels.ph_appointment_duration
          },
          {
            value: '%appointment_start_time%',
            label: this.$root.labels.ph_appointment_start_time
          },
          {
            value: '%appointment_end_time%',
            label: this.$root.labels.ph_appointment_end_time
          },
          {
            value: '%appointment_notes%',
            label: this.$root.labels.ph_appointment_notes
          },
          {
            value: '%appointment_price%',
            label: this.$root.labels.ph_appointment_price
          },
          {
            value: '%zoom_host_url%',
            label: this.$root.labels.ph_zoom_host_url
          },
          {
            value: '%zoom_join_url%',
            label: this.$root.labels.ph_zoom_join_url
          },
          {
            value: '%category_name%',
            label: this.$root.labels.ph_category_name
          },
          {
            value: '%service_description%',
            label: this.$root.labels.ph_service_description
          },
          {
            value: '%number_of_persons%',
            label: this.$root.labels.ph_booking_number_of_persons
          },
          {
            value: '%service_duration%',
            label: this.$root.labels.ph_service_duration
          },
          {
            value: '%service_name%',
            label: this.$root.labels.ph_service_name
          },
          {
            value: '%service_price%',
            label: this.$root.labels.ph_service_price
          },
          {
            value: '%recurring_appointments_details%',
            label: this.$root.labels.ph_recurring_appointments_details
          }
        ],

        eventPlaceholders: [
          {
            value: '%attendee_code%',
            label: this.$root.labels.ph_attendee_code
          },
          {
            value: '%event_name%',
            label: this.$root.labels.ph_event_name
          },
          {
            value: '%event_price%',
            label: this.$root.labels.ph_event_price
          },
          {
            value: '%event_cancel_url%',
            label: this.$root.labels.ph_event_cancel_url,
            parse: 'link'
          },
          {
            value: '%event_description%',
            label: this.$root.labels.ph_event_description
          },
          {
            value: '%number_of_persons%',
            label: this.$root.labels.ph_booking_number_of_persons
          },
          {
            value: '%event_period_date%',
            label: this.$root.labels.ph_event_period_date
          },
          {
            value: '%event_period_date_time%',
            label: this.$root.labels.ph_event_period_date_time
          },
          {
            value: '%zoom_host_url_date%',
            label: this.$root.labels.ph_zoom_host_url_date
          },
          {
            value: '%zoom_host_url_date_time%',
            label: this.$root.labels.ph_zoom_host_url_date_date
          },
          {
            value: '%zoom_join_url_date%',
            label: this.$root.labels.ph_zoom_join_url_date
          },
          {
            value: '%zoom_join_url_date_time%',
            label: this.$root.labels.ph_zoom_join_url_date_date
          },
          {
            value: '%event_start_date%',
            label: this.$root.labels.ph_event_start_date
          },
          {
            value: '%event_end_date%',
            label: this.$root.labels.ph_event_end_date
          },
          {
            value: '%event_start_time%',
            label: this.$root.labels.ph_event_start_time
          },
          {
            value: '%event_end_time%',
            label: this.$root.labels.ph_event_end_time
          },
          {
            value: '%event_start_date_time%',
            label: this.$root.labels.ph_event_start_date_time
          },
          {
            value: '%event_end_date_time%',
            label: this.$root.labels.ph_event_end_date_time
          },
          {
            value: '%employee_email%',
            label: this.$root.labels.ph_employee_email
          },
          {
            value: '%employee_first_name%',
            label: this.$root.labels.ph_employee_first_name
          },
          {
            value: '%employee_last_name%',
            label: this.$root.labels.ph_employee_last_name
          },
          {
            value: '%employee_full_name%',
            label: this.$root.labels.ph_employee_full_name
          },
          {
            value: '%employee_phone%',
            label: this.$root.labels.ph_employee_phone
          },
          {
            value: '%employee_note%',
            label: this.$root.labels.ph_employee_note
          },
          {
            value: '%employee_name_email_phone%',
            label: this.$root.labels.ph_employee_name_email_phone
          }
        ],

        customFieldsPlaceholders: [],

        extrasPlaceholders: [],

        couponsPlaceholders: [],

        placeholders: []

      }
    },

    mounted () {
      this.inlineSVG()
      this.addExtrasPlaceholders()
      this.addCouponsPlaceholders()
      this.addCustomFieldsPlaceholders()

      switch (this.userTypeTab) {
        case 'provider':
          this.commonPlaceholders.push(
            {
              value: '%coupon_used%',
              label: this.$root.labels.ph_coupon_used
            }
          )

          this.commonPlaceholders.push(
            {
              value: '%booked_customer%',
              label: this.$root.labels.ph_booked_customer
            }
          )

          if (this.type === 'email') {
            this.commonPlaceholders.push({
              value: '%employee_panel_url%',
              label: this.$root.labels.ph_employee_cabinet_url,
              parse: 'link'
            })
          }

          break
        case 'customer':
          this.appointmentPlaceholders.push(
            {
              value: '%appointment_cancel_url%',
              label: this.$root.labels.ph_appointment_cancel_url,
              parse: 'link'
            }
          )

          if (this.type === 'email') {
            this.commonPlaceholders.push({
              value: '%customer_panel_url%',
              label: this.$root.labels.ph_customer_cabinet_url,
              parse: 'link'
            })
          }

          break
      }

      switch (this.entity) {
        case 'event':
          this.placeholders = this.commonPlaceholders.concat(
            this.customFieldsPlaceholders.concat(
              this.eventPlaceholders.concat(
                this.couponsPlaceholders
              )
            )
          )
          break

        default:
          this.placeholders = this.commonPlaceholders.concat(
            this.customFieldsPlaceholders.concat(
              this.extrasPlaceholders.concat(
                this.appointmentPlaceholders.concat(
                  this.couponsPlaceholders
                )
              )
            )
          )
      }
    },

    methods: {
      getParsedCodeLabel (code) {
        if (code.parse === 'link') {
          for (let key in this.linksForParsing) {
            if (code.value === key) {
              return this.linksForParsing[key]
            }
          }
        }

        return ''
      },

      isDisabledInLite (codeValue) {
        return this.$root.isLite && [
          '%appointment_cancel_url%',
          '%customer_panel_url%',
          '%zoom_join_url%',
          '%zoom_host_url%',
          '%zoom_host_url_date%',
          '%zoom_host_url_date_time%',
          '%zoom_join_url_date%',
          '%zoom_join_url_date_time%',
          '%location_name%',
          '%location_description%',
          '%location_address%',
          '%location_phone%',
          '%recurring_appointments_details%'
        ].indexOf(codeValue) !== -1
      },

      closeDialog () {
        this.$emit('closeDialogPlaceholders')
      },

      copyCodeText (code) {
        let textArea = document.createElement('textarea')
        textArea.value = code
        document.body.appendChild(textArea)
        textArea.select()
        document.execCommand('Copy')
        document.body.removeChild(textArea)
      },

      addCustomFieldsPlaceholders () {
        for (let i = 0; i < this.customFields.length; i++) {
          if (this.customFields[i].type !== 'file') {
            this.customFieldsPlaceholders.push({
              value: '%custom_field_' + this.customFields[i].id + '%',
              label: this.customFields[i].label
            })
          }
        }
      },

      addCouponsPlaceholders () {
        if (this.userTypeTab === 'customer') {
          for (let i = 0; i < this.coupons.length; i++) {
            this.couponsPlaceholders.push({
              value: '%coupon_' + this.coupons[i].id + '%',
              label: this.coupons[i].code + ' [' + this.$root.labels.discount + ': ' + this.coupons[i].discount + ', ' + this.$root.labels.deduction + ': ' + this.coupons[i].deduction + this.getCurrencySymbol() + ']'
            })
          }
        }
      },

      addExtrasPlaceholders () {
        for (let i = 0; i < this.categories.length; i++) {
          for (let j = 0; j < this.categories[i].serviceList.length; j++) {
            for (let k = 0; k < this.categories[i].serviceList[j].extras.length; k++) {
              this.extrasPlaceholders.push({
                value: '%service_extra_' + this.categories[i].serviceList[j].extras[k].id + '_name%',
                label: this.categories[i].serviceList[j].extras[k].name
              })

              this.extrasPlaceholders.push({
                value: '%service_extra_' + this.categories[i].serviceList[j].extras[k].id + '_quantity%',
                label: this.categories[i].serviceList[j].extras[k].name
              })

              this.extrasPlaceholders.push({
                value: '%service_extra_' + this.categories[i].serviceList[j].extras[k].id + '_price%',
                label: this.categories[i].serviceList[j].extras[k].name
              })
            }
          }
        }
      }
    },

    computed: {
      sortedPlaceholders () {
        return this.placeholders.sort((a, b) => (a.value > b.value) ? 1 : ((b.value > a.value) ? -1 : 0))
      }
    }
  }
</script>
