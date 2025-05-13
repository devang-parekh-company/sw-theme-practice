import "./component";
import "./preview";

Shopware.Service("cmsService").registerCmsBlock({
  name: "dailymotion",
  label: "Dailymotions Video",
  category: "video",
  component: "sw-cms-block-dailymotion",
  previewComponent: "sw-cms-preview-dailymotion",
  defaultConfig: {
    marginBottom: "20px",
    marginTop: "20px",
    marginLeft: "20px",
    marginRight: "20px",
    sizingMode: "boxed",
  },
  slots: {
    video: "dailymotion",
  },
});
