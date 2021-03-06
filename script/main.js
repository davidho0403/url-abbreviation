$(() => {
    let url_name = getUrl();
    searchUrl(url_name);
});

const getUrl = () => {
    let param = new URLSearchParams(window.location.search);
    return param.get("n");
};

const searchUrl = (url_name) => {
    let data = {
        url_name: url_name,
    };
    let json = JSON.stringify(data);
    $.ajax({
        url: "backend/core.php",
        method: "POST",
        data: json,
        success: (res) => {
            res ? jump(res[0]["url_dest"]) : displayError();
        },
    });
};

const jump = (url) => {
    document.location.href = "https://" + url;
};

const displayError = () => {
    $("#title").addClass("hide");
    $("#error-text").removeClass("hide");
};
