const { PluginBaseClass } = window;

export default class ZenitPlatformGravityChild extends PluginBaseClass {
  init() {
    window.addEventListener("input", this.onInput.bind(this));
    this.customerGroupList = [];
    this.customerGroupDiv = document.querySelector(".customer-group-results");
  }

  onInput(event) {
    if (event.target.matches("[data-customer-group-plugin]")) {
      const value = event.target.value;

      if (value.length >= 3) {
        this.getCustomerGroup(value);
      } else {
        if (this.customerGroupDiv) {
          this.customerGroupDiv.innerHTML = "";
        }
      }
    }
  }
  async getCustomerGroup(value) {
    const baseUrl = this.el.getAttribute("data-base-url");
    const result = await fetch(`${baseUrl}/get-customer-group?term=${value}`)
      .then((response) => response.json())
      .then((data) => {
        this.customerGroupList = data.data;
        const list = document.createElement("ul");
        this.customerGroupList.forEach((item) => {
          const listItem = document.createElement("li");
          listItem.textContent = item.name;
          list.appendChild(listItem);
        });

        if (this.customerGroupDiv) {
          this.customerGroupDiv.innerHTML = "";
          this.customerGroupDiv.appendChild(list);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
    return result;
  }
}
