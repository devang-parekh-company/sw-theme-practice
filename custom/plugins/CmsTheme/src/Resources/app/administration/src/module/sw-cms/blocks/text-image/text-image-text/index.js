import CMS from "../../../constant/sw-cms.constant";

import "./preview";
import "./component";

Shopware.Service("cmsService").registerCmsBlock({
  name: "text-image-text",
  label: "Custom text-image-text",
  category: "text-image",
  component: "sw-cms-block-text-image-text",
  previewComponent: "sw-cms-preview-text-image-text",
  defaultConfig: {
    marginBottom: "20px",
    marginTop: "20px",
    marginLeft: "20px",
    marginRight: "20px",
    sizingMode: "boxed",
  },
  slots: {
    left: {
      type: "text",
      default: {
        config: {
          content: {
            source: "static",
            value: `
                        <h2 style="text-align: center;">Symfony</h2>
                        <p style="text-align: center;">Shopware is an open-source e-commerce platform.</p>
                        `.trim(),
          },
        },
      },
    },
    center: {
      type: "custom-image",
      default: {
        config: {
          displayMode: { source: "static", value: "cover" },
        },
        data: {
          media: {
            value: CMS.MEDIA.previewGlasses,
            source: "default",
          },
        },
      },
    },
    right: {
      type: "text",
      default: {
        config: {
          content: {
            source: "static",
            value: `
                        <h2 style="text-align: center;">Vue js</h2>
                        <p style="text-align: center;">Shopware is an open-source e-commerce platform.</p>
                        `.trim(),
          },
        },
      },
    },
  },
});
