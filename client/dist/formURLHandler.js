window.addEventListener('DOMContentLoaded', (event) => {
  const articleFilterForm = document.getElementById('ArticleFilterForm');

  articleFilterForm.addEventListener('submit', (e) => {
    let target = e.target;
    let formData = {};

    for (let i = 0; i < target.length; i++) {
      let element = target.elements[i];
      let name = element.name;

      if (element.type === 'text' && element.value) {
        formData[name] = element.value;
      }

      if (element.type === 'checkbox' && element.checked) {
        name = element.name.substr(0, element.name.indexOf('['));
        formData[name] = formData[name] ?? [];
        formData[name].push(element.value);
      }

      if (element.type === 'select-one' && element.value) {
        formData[name] = element.value;
      }
    }

    let currentURL = location.protocol + '//' + location.host + location.pathname;
    let newQueryParams = new URLSearchParams(formData).toString();

    window.location.href = `${currentURL}?${newQueryParams}`;

    e.preventDefault();
  })

});

