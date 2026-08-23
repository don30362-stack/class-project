function addcart(p_id) {
    var qty = $("#qty").val();
    if (qty <= 0) {
        alert("產品數量不得為或為負數，請再修改數量!");
        return (false);
    }
    if (qty == undefined) {
        qty = 1;
    } else if (qty >= 50) {
        alert("由於採購數量限制，產品數量將限制在50以下!");
        return (false);
    }

    $.ajax({
        url: 'addcart.php',
        type: 'get',
        dataType: 'json',
        data: {
            p_id: p_id,
            qty: qty
        },
        success: function (data) {
            if (data.c == true) {
                alert(data.m);
                window.location.reload();
            } else {
                alert(data.m);
            }
        },
        error: function (data) {
            alert("系統目前無法連接到後台資料庫。");
        }
    });
}


document.addEventListener("DOMContentLoaded", function () {

    // 1. 手機版商品專區大按鈕點擊
    const productDropdownButton = document.querySelector('.product-dropdown-toggle');
    const productDropdownMenu = document.querySelector('.product-dropdown > .dropdown-menu');

    if (productDropdownButton && productDropdownMenu) {
        productDropdownButton.addEventListener('click', function (e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                e.stopPropagation();
                productDropdownMenu.classList.toggle('show');
                const isOpen = productDropdownMenu.classList.contains('show');
                this.setAttribute('aria-expanded', isOpen);
            }
        });
    }

    // ⭐ 2. 手機版第二層選單全新邏輯：修復點擊與穿透問題
    document.querySelectorAll('.submenu-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                e.stopPropagation();

                // 使用 currentTarget 確保永遠拿到 <button> 本身，不管有沒有點到 <i>
                const currentBtn = e.currentTarget;
                currentBtn.classList.toggle('open');

                // 往上找最近的分類外層 <li>
                const parentLi = currentBtn.closest('.nav-item.dropend');

                if (parentLi) {
                    // 精準尋找這個大分類旗下的那個子選單 .submenu
                    const submenu = parentLi.querySelector('.submenu');
                    if (submenu) {
                        submenu.classList.toggle('show');
                    }
                }
            }
        });
    });

    function resetMobileDropdowns() {
        if (window.innerWidth >= 992) {

            // 關閉商品專區第一層
            productDropdownMenu?.classList.remove('show');

            productDropdownButton?.setAttribute(
                'aria-expanded',
                'false'
            );

            // 關閉所有第二層
            document.querySelectorAll('.submenu')
                .forEach(function (submenu) {
                    submenu.classList.remove('show');
                });

            // 箭頭恢復
            document.querySelectorAll('.submenu-toggle-btn')
                .forEach(function (button) {
                    button.classList.remove('open');
                });
        }
    }

    window.addEventListener('resize', resetMobileDropdowns);
});