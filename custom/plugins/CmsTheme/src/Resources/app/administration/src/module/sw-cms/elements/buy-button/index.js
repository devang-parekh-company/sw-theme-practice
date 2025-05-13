import "./preview";
import "./config";
import "./component";

/**
 * @private
 * @sw-package discovery
 */
Shopware.Service("cmsService").registerCmsElement({
  name: "buy-button",
  label: "sw-cms.elements.buyButton.label",
  component: "sw-cms-el-buy-button",
  configComponent: "sw-cms-el-config-buy-button",
  previewComponent: "sw-cms-el-preview-buy-button",
  disabledConfigInfoTextKey:
    "sw-cms.elements.buyButton.infoText.tooltipSettingDisabled",
  defaultConfig: {
    product: {
      source: "static",
      value: null,
      required: true,
      entity: {
        name: "product",
        criteria: new Shopware.Data.Criteria(1, 25).addAssociation(
          "deliveryMedia"
        ),
      },
    },
    alignment: {
      source: "static",
      value: null,
    },
  },
  defaultData: {
    product: {
      name: "Lorem Ipsum dolor",
      productNumber: "XXXXXX",
      minPurchase: 1,
      deliveryTime: {
        name: "1-3 days",
      },
      price: [{ gross: 0.0 }],
    },
  },
  collect: Shopware.Service("cmsService").getCollectFunction(),
});
