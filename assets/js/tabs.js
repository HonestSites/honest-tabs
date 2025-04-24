function getPass(linkId)
{
    return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();

        xhttp.open("GET", `/ajax/${linkId}/getLinkPass`, true);

        xhttp.onload = function() {
            console.log(this.responseText);
            resolve(this.responseText);
        }

        xhttp.onerror = function() {
            // Network error
            console.error('Network Error');
            reject();
        };

        xhttp.send();
    });
}

async function copyText(text)
{
    await navigator.clipboard.writeText(text);
}

async function copyPass(linkId)
{
    let pass = await getPass(linkId);
    await navigator.clipboard.writeText(pass);
}

async function showPass(linkId) {
    let pass = await getPass(linkId);
    let eleId = "pass-" + linkId;
    document.getElementById(eleId).innerHTML = `<a href="javascript:void(0);" onclick="hidePass(${linkId});">${pass}</a>`;
}

function hidePass(linkId) {
    let eleId = "pass-" + linkId;
    document.getElementById(eleId).innerHTML = `<a href="javascript:void(0);" onclick="showPass(${linkId});">show password</a>`;
}