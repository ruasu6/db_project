function saveScrollPosition() {
    // 將當前的滾動位置存儲到 sessionStorage 中
    sessionStorage.setItem('scrollPosition', window.pageYOffset);
  }
  
  // 在頁面載入時檢查是否有存儲的滾動位置
  window.onload = function() {
    var scrollPosition = sessionStorage.getItem('scrollPosition');
    if (scrollPosition) {
      // 如果有存儲的滾動位置，則將頁面滾動到該位置
      window.scrollTo(0, scrollPosition);
      // 清除存儲的滾動位置
      sessionStorage.removeItem('scrollPosition');
    }
  };
  