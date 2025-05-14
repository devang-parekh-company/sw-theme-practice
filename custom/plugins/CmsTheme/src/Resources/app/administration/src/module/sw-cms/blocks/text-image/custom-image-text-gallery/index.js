import CMS from '../../../constant/sw-cms.constant';
import "./preview";
import "./component";

Shopware.Service("cmsService").registerCmsBlock({
  name: "custom-image-text-gallery",
  label: "Custom Image Text Gallery Block",
  category: "text-image",
  component: "sw-cms-block-custom-image-text-gallery",
  previewComponent: "sw-cms-preview-custom-image-text-gallery",
  defaultConfig: {
    marginBottom: "20px",
    marginTop: "20px",
    marginLeft: "20px",
    marginRight: "20px",
    sizingMode: "boxed",
  },
  slots: {
    "left-image": {
      type: "image",
      default: {
        config: {
          displayMode: { source: "static", value: "cover" },
        },
        data: {
          media: {
            value: "bundles/administration/static/img/cms/preview_camera_large.jpg",
            source: "default",
          },
        },
      },
    },
    "left-text": {
      type: "text",
      default: {
        config: {
          content: {
            source: "static",
            value: `
              <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
              <p style="text-align: center;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
              sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
              sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
            `.trim(),
          },
        },
      },
    },
    "left-button": {
      type: "buy-button",
      default: {
        config: {
          name: {
            source: "static",
            value: "Shop",
            required: true,
          },
          link: {
            source: "static",
            value: null,
          },
        },
      },
    },

    "center-left-image": {
      type: "image",
      default: {
        config: {
          displayMode: { source: "static", value: "cover" },
        },
        data: {
          media: {
            value: "bundles/administration/static/img/cms/preview_glasses_large.jpg",
            source: "default",
          },
        },
      },
    },
    "center-left-text": {
      type: "text",
      default: {
        config: {
          content: {
            source: "static",
            value: `
              <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
              <p style="text-align: center;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
              sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
              sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
            `.trim(),
          },
        },
      },
    },
    "center-left-button": {
      type: "buy-button",
      default: {
        config: {
          name: {
            source: "static",
            value: "Shop",
            required: true,
          },
          link: {
            source: "static",
            value: null,
          },
        },
      },
    },

    "center-right-image": {
      type: "image",
      default: {
        config: {
          displayMode: { source: "static", value: "cover" },
        },
        data: {
          media: {
            value: "bundles/administration/static/img/cms/preview_plant_large.jpg",
            source: "default",
          },
        },
      },
    },
    "center-right-text": {
      type: "text",
      default: {
        config: {
          content: {
            source: "static",
            value: `
              <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
              <p style="text-align: center;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
              sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
              sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
            `.trim(),
          },
        },
      },
    },
    "center-right-button": {
      type: "buy-button",
      default: {
        config: {
          name: {
            source: "static",
            value: "Shop",
            required: true,
          },
          link: {
            source: "static",
            value: null,
          },
        },
      },
    },

    "right-image": {
      type: "image",
      default: {
        config: {
          displayMode: { source: "static", value: "cover" },
        },
        data: {
          media: {
            value: "bundles/administration/static/img/cms/preview_camera_large.jpg",
            source: "default",
          },
        },
      },
    },
    "right-text": {
      type: "text",
      default: {
        config: {
          content: {
            source: "static",
            value: `
              <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
              <p style="text-align: center;">Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
              sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
              sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
            `.trim(),
          },
        },
      },
    },
    "right-button": {
      type: "buy-button",
      default: {
        config: {
          name: {
            source: "static",
            value: "Shop",
            required: true,
          },
          link: {
            source: "static",
            value: null,
          },
        },
      },
    },
  },
});
