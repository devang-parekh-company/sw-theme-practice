import template from "./sw-cms-el-preview-custom-image.html.twig";
import "./sw-cms-el-preview-custom-image.scss";

const { Component } = Shopware;

Component.register("sw-cms-el-preview-custom-image", {
  template,

  computed: {
    assetFilter() {
      return Shopware.Filter.getByName("asset");
    },
  },
});
