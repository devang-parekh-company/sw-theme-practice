const {Component, Mixin, Data: {Criteria}} = Shopware;

Shopware.Component.extend('license-configuration-create', 'license-configuration-detail', {
    methods: {
        getLicenseData() {
            this.licenses = this.repository.create(Shopware.Context.api);
        },

        async onClickSave() {
            this.isLoading = true;
            let category = this.licenses.categoryId;
            let productId = this.licenses.productId;
            let customerGroupId = this.licenses.customerGroupId;

            if (!category || !productId || !customerGroupId){
                this.createNotificationError({
                    title: this.$tc('Error'),
                    message: this.$tc('Please fill in all required fields'),
                });
                this.isLoading = false;
            }

            const searchResult = await this.isExistingLicenseData(this.licenses);

            if (searchResult.length > 0) {
                this.createNotificationError({
                    title: this.$tc('Error'),
                    message: this.$tc('A record with this Customer Group, Category, and Product combination already exists.'),
                });
                this.isLoading = false;
            } else {
                this.repository
                    .save(this.licenses, Shopware.Context.api)
                    .then(() => {
                        this.createNotificationSuccess({
                            message: this.$tc('Data has been saved successfully.'),
                        });
                        this.isLoading = false;
                        this.$router.push({name: 'license.configuration.list'});
                    }).catch((exception) => {
                    this.isLoading = false;
                    this.createNotificationError({
                        title: this.$tc('Error'),
                        message: this.$tc(exception),
                    });
                });
            }
        }
    }
});
