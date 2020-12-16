export default {

  data: () => ({
    dialogCustomer: false
  }),

  methods: {
    getInitCustomerObject () {
      return {
        id: 0,
        firstName: '',
        lastName: '',
        externalId: '',
        phone: '',
        countryPhoneIso: null,
        email: '',
        gender: '',
        birthday: null,
        note: '',
        status: 'visible',
        type: 'customer',
        countPendingAppointments: 0
      }
    }
  }
}
