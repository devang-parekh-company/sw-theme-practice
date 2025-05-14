import template from "./sw-cms-el-buy-button.html.twig";
import "./sw-cms-el-buy-button.scss";

const { Mixin } = Shopware;

Shopware.Component.register("sw-cms-el-buy-button", {
  template,

  compatConfig: Shopware.compatConfig,

  mixins: [Mixin.getByName("cms-element"), Mixin.getByName("placeholder")],

  computed: {
    name() {
      return this.element?.config?.name?.value ?? "Shop";
    },
    redirectTo() {
      return this.element.config?.link?.value ?? null;
    },
  },

  created() {
    this.createdComponent();
  },

  methods: {
    createdComponent() {
      this.initElementConfig("buy-button");
      this.initElementData("buy-button");
    },
  },
});
