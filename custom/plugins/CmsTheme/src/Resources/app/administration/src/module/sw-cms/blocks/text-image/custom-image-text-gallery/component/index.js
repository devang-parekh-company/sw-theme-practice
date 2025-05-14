import template from "./sw-cms-block-custom-image-text-gallery.html.twig";
import "./sw-cms-block-custom-image-text-gallery.scss";

Shopware.Component.register("sw-cms-block-custom-image-text-gallery", {
  template,

  compatConfig: Shopware.compatConfig,
});
