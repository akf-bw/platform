import template from './sw-customer-login-as-customer-modal.html.twig';
import './sw-customer-login-as-customer-modal.scss';
import ApiService from '../../../../core/service/api.service';

const { Service, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('notification'),
    ],

    props: {
        customer: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            salesChannelDomains: [],
        };
    },

    computed: {
        modalTitle() {
            return this.$tc('sw-customer.loginAsCustomerModal.modalTitle');
        },

        salesChannelDomainRepository() {
            return this.repositoryFactory.create('sales_channel_domain');
        },

        currentUser() {
            return Shopware.State.get('session').currentUser;
        },

        salesChannelDomainCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('salesChannel');

            if (this.customer && this.customer.boundSalesChannelId) {
                criteria.addFilter(Criteria.equals('salesChannelId', this.customer.boundSalesChannelId));
            }

            return criteria;
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        async createdComponent() {
            this.fetchSalesChannelDomains();
        },

        async onSalesChannelDomainMenuItemClick(salesChannelId, salesChannelDomainUrl) {
            await Service('contextStoreService').generateLoginAsCustomerToken(
                this.customer.id,
                salesChannelId,
            ).then((response) => {
                const handledResponse = ApiService.handleResponse(response);

                this.redirectToSalesChannelUrl(
                    salesChannelDomainUrl,
                    handledResponse.token,
                    this.customer.id,
                    this.currentUser?.id,
                );
            }).catch(() => {
                this.createNotificationError({
                    message: this.$tc('sw-customer.detail.notificationLoginAsCustomerErrorMessage'),
                });
            });
        },

        onCancel() {
            this.$emit('modal-close');
        },

        fetchSalesChannelDomains() {
            this.salesChannelDomainRepository.search(
                this.salesChannelDomainCriteria,
                Shopware.Context.api,
            ).then((loadedDomains) => {
                this.salesChannelDomains = loadedDomains;
            });
        },

        redirectToSalesChannelUrl(salesChannelDomainUrl, token, customerId, userId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `${salesChannelDomainUrl}/account/login/imitate-customer`;
            form.target = '_blank';
            document.body.appendChild(form);

            this.createHiddenInput(form, 'token', token);
            this.createHiddenInput(form, 'customerId', customerId);
            this.createHiddenInput(form, 'userId', userId);

            form.submit();
            form.remove();
        },

        createHiddenInput(form, name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        },
    },
};
