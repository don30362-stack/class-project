function showSiteToast(message) {
    let container = document.getElementById('site-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'site-toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1100';
        container.innerHTML = '<div id="site-toast" class="toast align-items-center text-bg-dark border-0" role="status" aria-live="polite" aria-atomic="true"><div class="d-flex"><div class="toast-body"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="關閉"></button></div></div>';
        document.body.appendChild(container);
    }
    const toastElement = document.getElementById('site-toast');
    toastElement.querySelector('.toast-body').textContent = message;
    bootstrap.Toast.getOrCreateInstance(toastElement, { delay: 1200 }).show();
}

function showCartFeedback(message) {
    const feedback = document.getElementById('cart-feedback');
    if (feedback) {
        feedback.textContent = message;
        feedback.classList.remove('d-none');
    }
}

function addcart(p_id) {
    let qty = $("#qty").val();
    if (qty === undefined) qty = 1;
    qty = Number(qty);
    if (!Number.isInteger(qty) || qty < 1 || qty > 49) {
        const quantityError = document.getElementById('product-quantity-error');
        if (quantityError) quantityError.textContent = '商品數量請輸入 1～49。';
        document.getElementById('qty')?.focus();
        return false;
    }

    const addButton = document.getElementById('button01');
    if (addButton?.disabled) return false;
    document.getElementById('cart-feedback')?.classList.add('d-none');
    if (addButton) addButton.disabled = true;

    $.ajax({
        url: 'actions/addcart.php',
        type: 'post',
        dataType: 'json',
        data: {
            p_id: p_id,
            qty: qty,
            csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        success: function (data) {
            if (data.c == true) {
                showSiteToast(data.m);
                window.setTimeout(function () { window.location.reload(); }, 700);
            } else {
                showCartFeedback(data.m);
            }
        },
        error: function () {
            showCartFeedback('系統目前無法連接，請稍後再試。');
        },
        complete: function () {
            if (addButton) addButton.disabled = false;
        }
    });
    return false;
}


document.addEventListener("DOMContentLoaded", function () {
    // 與 navbar-expand-xl 及 CSS 的桌面版斷點保持一致。
    const navbarDesktopBreakpoint = 1200;

    // 1. 手機版商品專區大按鈕點擊
    const productDropdownButton = document.querySelector('.product-dropdown-toggle');
    const productDropdownMenu = document.querySelector('.product-dropdown > .dropdown-menu');

    if (productDropdownButton && productDropdownMenu) {
        productDropdownButton.addEventListener('click', function (e) {
            if (window.innerWidth < navbarDesktopBreakpoint) {
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
            if (window.innerWidth < navbarDesktopBreakpoint) {
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
        if (window.innerWidth >= navbarDesktopBreakpoint) {

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

function btn_confirmLink(message, url) {
    if (message == "" || url == "") {
        return false;
    }
    if (confirm(message)) {
        window.location = url;
    }
    return false;
}
