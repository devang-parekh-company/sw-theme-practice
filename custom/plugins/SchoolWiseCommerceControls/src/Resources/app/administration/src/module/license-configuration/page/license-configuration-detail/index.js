import template from './license-configuration-detail.html.twig';

const { Component, Mixin, Data: { Criteria } } = Shopware;
const {mapPropertyErrors} = Shopware.Component.getComponentHelper();

Component.register('license-configuration-detail', {
    template,

    inject: [
        'repositoryFactory',
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    data() {
        return {
            processSuccess: false,
            isLoading: false,
            licenses: null
        };
    },

    created() {
        this.getLicenseData();
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('school_licences_information');
        },
        ...mapPropertyErrors(
            'licenses', [
                'customerGroupId',
                'categoryId',
                'productId'
            ]
        ),

        activeCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('active', true));
            return criteria;
        },
    },

    methods: {
        getLicenseData() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((entity) => {
                    this.licenses = entity;
                });
        },

        isExistingLicenseData() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('customerGroupId', this.licenses.customerGroupId));
            criteria.addFilter(Criteria.equals('categoryId', this.licenses.categoryId));
            criteria.addFilter(Criteria.equals('productId', this.licenses.productId));

            return this.repository.search(criteria, Shopware.Context.api);
        },

        async onClickSave() {
            this.isLoading = true;
            const searchResult = await this.isExistingLicenseData(this.licenses);

            if (searchResult.length > 0) {
                this.createNotificationError({
                    title: this.$tc('Error'),
                    message: this.$tc('The entry you are trying to add is already exists. Please check your data and try again.'),
                });
                this.isLoading = false;
            } else {

                this.repository
                    .save(this.licenses, Shopware.Context.api)
                    .then(() => {
                        this.createNotificationSuccess({
                            message: this.$tc('Data has been updated successfully.'),
                        });
                        this.isLoading = false;
                        this.processSuccess = true;
                    }).catch((exception) => {
                    this.createNotificationError({
                        title: this.$tc('Error'),
                        message: this.$tc(exception),
                    });
                    this.isLoading = false;
                });
            }
        },

        saveFinish() {
            this.processSuccess = false;
        }
    }
});