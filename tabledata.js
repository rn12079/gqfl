//post or retrieve data depending on URL and data sent
// for post form using addparty.fetch.php , for retrieving tabledata.fetch.php
const postData = async (url, data) => {
  //console.log("postdata", data);
  const response = await fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  });
  return await response.json();
};

async function postUploadData(fd, fileexists) {
  if (!fileexists) {
    console.log("file does not exists, errors");
    return "Failure";
  }

  const mydata = {
    method: "POST",
    headers: {
      Accept: "application/json",
    },
    mode: "no-cors",
    body: fd,
  };
  console.log("attempting upload");
  const response = await fetch("fileupload.php", mydata);
  return await response.json();
}

//get option values and names for select
async function getSelectOptions(data, target) {
  let res = "";
  // requesting data from Fetch api;
  await postData("getdata.php", data).then((opt) => {
    opt.forEach((option) => {
      res += `<option value='${option.id}'>${
        option.name === null ? "" : option.name
      }</option>`;
    });

    target.innerHTML = res;
  });
}
