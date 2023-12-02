var apiKeyT = "ccfc6902624f5980de6bc284bd7b85e3";
var resultT = document.querySelector(".tmdb-result");
const searchBox = document.getElementById('searchBox');
const suggestionsList = document.getElementById('suggestions');


// 當使用者輸入關鍵字時觸發
document.getElementById('searchBox').addEventListener('input', function() {
    // 取得使用者輸入的關鍵字
    var keyword = this.value;
    var url = 
    "https://api.themoviedb.org/3/search/multi?api_key=" + apiKeyT + "&language=zh-TW" + "&query=";
    // 利用 AJAX 向伺服器發送請求，獲取相關的建議詞或短語

    
    var xhr = new XMLHttpRequest();

    xhr.open('GET', url + keyword, true);
    xhr.onreadystatechange = function() {
        var searchForm = document.createElement("form");
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            searchForm.setAttribute("method", "POST");
            searchForm.setAttribute("action", "search/search.inc.php");
            searchForm.classList.add("searchForm");
            const suggestions = response.results.map(item => item.title || item.name);
            const images = response.results.map(item => item.poster_path);
            const ids = response.results.map(item => item.id);
            const types = response.results.map(item => item.media_type);
            var area = document.createElement("div");
            area.classList.add("area");
            area.setAttribute("id", "scroll");

            function getDetail(id, type) {
                return new Promise(function(resolve, reject) {
                    var urlT = "https://api.themoviedb.org/3/" + type + "/" + id + "?api_key=" + apiKeyT;
                    var xhr = new XMLHttpRequest();
                    xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        resolve(JSON.parse(xhr.responseText));
                    } else {
                        reject(xhr.statusText);
                    }
                    };
                    xhr.onerror = function() {
                    reject(xhr.statusText);
                    };
                    xhr.open("GET", urlT, true);
                    xhr.send();
                });
            }

            suggestionsList.innerHTML = '';
        
            // 使用 Promise.all() 等待所有請求完成後再處理回應
            Promise.all(response.results.map(function(item) {
                return getDetail(item.id, item.media_type);
            })).then(function(details) {
                const genres = details.map(detail => detail.genres.map(genrelist => genrelist.id));
                console.log(genres);
                for (let i = 0; i < suggestions.length; ++i) {
                    const suggestion = suggestions[i];
                    const img = images[i];
                    const id = ids[i];
                    const type = types[i];
                    const genre = genres[i];
                    // do something with suggestion, img, id, type, and genre
                    
                var text = document.createElement("div");
                text.classList.add("text");

                

                var formItem = document.createElement("div");
                formItem.classList.add("formItem");

                var suggestionItem = document.createElement('label');
                suggestionItem.setAttribute("for", "search-result" + i);
                suggestionItem.classList.add("title");
                suggestionItem.innerHTML = suggestion;

                var checkbox = document.createElement('input');
                checkbox.type = "radio";
                checkbox.name = "search-result";
                checkbox.setAttribute("id", "search-result" + i);
                checkbox.setAttribute("data-target", i);
                checkbox.value = id;

                var typeName = document.createElement("input");
                typeName.name = "search-result-type" + checkbox.value;
                typeName.type = "hidden";
                typeName.setAttribute("id", i);
                typeName.value = type;

                var genreName = document.createElement("input");
                genreName.type = "hidden";
                genreName.value =  genre.join(", ");// 將 genres 陣列轉換為字串
                genreName.name = "search-result-genre" + checkbox.value;
                text.appendChild(genreName);

                var videoName = document.createElement("input");
                videoName.name = "search-result-name" + checkbox.value;
                videoName.type = "hidden";
                videoName.setAttribute("id", i);
                videoName.value = suggestion;
                
                var imgItem = document.createElement('img');
                if (img !== null){
                    imgItem.src = "https://image.tmdb.org/t/p/w92/" + img;
                }

                
                formItem.appendChild(imgItem);
                text.appendChild(checkbox)
                text.appendChild(suggestionItem);
                text.appendChild(typeName);
                text.appendChild(videoName);
                formItem.appendChild(text);
                area.appendChild(formItem);
                // searchForm.appendChild(formItem);

                if(i === suggestions.length - 1){
                    var button = document.createElement("input");
                    button.type = "submit";
                    button.name = "submit";
                    button.classList.add("searchSubmit");
                    button.innerHTML = "submit";
                    searchForm.appendChild(area);
                    searchForm.appendChild(button);
                    suggestionsList.appendChild(searchForm);
                    console.log(searchForm);
                }
                }
            }).catch(function(error) {
                console.log(error);
            });
        }
      }
    xhr.send();
  });