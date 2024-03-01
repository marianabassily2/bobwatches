function showFormValidationMessages() {
  document.getElementById("address-frm").classList.add("was-validated");
}

function showSuccessAlert() {
  document.getElementById('success-alert').classList.add("show");
  setTimeout(function() {
      document.getElementById('success-alert').classList.remove("show");
  }, 1000);

}

function enableLoading() {
  document.getElementById("overlay").style.display = "flex";
}

function disableLoading() {
  document.getElementById("overlay").style.display = "none";
}

function enableSubmission() {
  document.getElementById('frm-submit-btn').disabled = false;
}

function disableSubmission() {
  document.getElementById('frm-submit-btn').disabled = true;
}

function enableFormFields() {
  document.getElementById("frm-fieldset").disabled = false;
}

function disableFormFields() {
  document.getElementById("frm-fieldset").disabled = true;
}

function isJsonString(str) {
  try {
      JSON.parse(str);
  } catch (e) {
      return false;
  }
  return true;
}

function validateAddress() {
  let streetAddress = document.getElementById("streetAddress").value;
  let state = document.getElementById("state").value;
  let ZIPCode = document.getElementById("ZIPCode").value;

  if (streetAddress && state && ZIPCode) {
      disableFormFields();
      enableLoading();
      let req = new XMLHttpRequest();
      url = "server/server.php?streetAddress=" + streetAddress + "&state=" + state + "&ZIPCode=" + ZIPCode;
      req.open("GET", url, true);
      req.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              if (isJsonString(req.responseText)) {
                  const response = JSON.parse(req.responseText);
                  if (!response.error) {
                      let address = response.address;
                      let result = confirm("Would You Like to use the standarized version? \n" + Object.values(address).join("\r\n"));
                      if (result == true) {
                          for (const property in address) {
                              if (document.getElementById(property))
                                  document.getElementById(property).value = address[property];
                          }
                          document.getElementById('ZIPCode').value = address['ZIPCode'] + '-' + address['ZIPPlus4'];
                      }
                  }
              }
              enableSubmission();
              enableFormFields();
              disableLoading();

          }

      };
      req.send();
  }

}


function save() {
  let addressForm = document.getElementById("address-frm");
  let checkValidity = addressForm.checkValidity();
  let data = new FormData();

  if (checkValidity) {
      let inputs = addressForm.querySelectorAll("input ,select");
      for (let i = 0; i < inputs.length; i++) {
          data.append(inputs[i]['name'], inputs[i]['value']);
      }
      let req = new XMLHttpRequest();
      url = "server/server.php";
      req.open("POST", url, true);
      req.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              const response = JSON.parse(req.responseText);
              if (response.success) {
                  addressForm.reset();
                  showSuccessAlert();
              } else if (response.errors) {
                  showFormValidationMessages();
              }
          }
      };
      req.send(data);

  } else {
      showFormValidationMessages();
  }
  return true;
}