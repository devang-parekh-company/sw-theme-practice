import template from "./sw-cms-preview-dailymotion.html.twig";
import "./sw-cms-preview-dailymotion.scss";

Shopware.Component.register("sw-cms-preview-dailymotion", {
  template,

  computed: {
    assetFilter() {
      return Shopware.Filter.getByName("asset");
    },
  },
});
