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
            value: "administration/static/img/cms/preview_camera_large.jpg",
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

    "button-1": {
      type: "buy-button",
      default: {
        config: {
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
          }
        },
        data: {
          product: {
            name: "Lorem Ipsum dolor",
            productNumber: "XXXXXX", 
            minPurchase: 1,
            deliveryTime: {
              name: "1-3 days",
            },
            price: [{ gross: 0.0 }],
          }
        }
      }
    },
    "center-image": {
      type: "image",
      default: {
        config: {
          displayMode: { source: "static", value: "cover" },
        },
        data: {
          media: {
            value: "administration/static/img/cms/preview_glasses_large.jpg",
            source: "default",
          },
        },
      },
    },
    "center-text": {
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
    "button-2": {
      type: "buy-button",
      default: {
        config: {
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
          }
        },
        data: {
          product: {
            name: "Lorem Ipsum dolor",
            productNumber: "XXXXXX", 
            minPurchase: 1,
            deliveryTime: {
              name: "1-3 days",
            },
            price: [{ gross: 0.0 }],
          }
        }
      }
    },
    "right-image": {
      type: "image",
      default: {
        config: {
          displayMode: { source: "static", value: "cover" },
        },
        data: {
          media: {
            value: "administration/static/img/cms/preview_plant_large.jpg",
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
    "button-3": {
      type: "buy-button",
      default: {
        config: {
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
          }
        },
        data: {
          product: {
            name: "Lorem Ipsum dolor",
            productNumber: "XXXXXX", 
            minPurchase: 1,
            deliveryTime: {
              name: "1-3 days",
            },
            price: [{ gross: 0.0 }],
          }
        }
      }
    },
    "bottom-right-image": {
      type: "image",
      default: {
        config: {
          displayMode: { source: "static", value: "cover" },
        },
        data: {
          media: {
            value: "administration/static/img/cms/preview_camera_large.jpg",
            source: "default",
          },
        },
      },
    },
    "bottom-right-text": {
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
    "button-4": {
      type: "buy-button",
      default: {
        config: {
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
          }
        },
        data: {
          product: {
            name: "Lorem Ipsum dolor",
            productNumber: "XXXXXX", 
            minPurchase: 1,
            deliveryTime: {
              name: "1-3 days",
            },
            price: [{ gross: 0.0 }],
          }
        }
      }
    },
  },
});
