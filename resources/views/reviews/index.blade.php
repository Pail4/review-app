<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Отзывы</title>
    <!-- подключи стили / vite, если нужно -->
</head>
<body>
    <h1>Отзывы</h1>

    <div id="form-area">
        <form id="review-form">
            <div>
                <label>Имя *</label><br>
                <input type="text" name="name" id="name">
                <div class="error" id="error-name"></div>
            </div>

            <div>
                <label>Текст отзыва *</label><br>
                <textarea name="body" id="body"></textarea>
                <div class="error" id="error-body"></div>
            </div>

            <div>
                <label>Оценка *</label><br>
                <select name="rating" id="rating">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3" selected>3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <div class="error" id="error-rating"></div>
            </div>

            <button type="submit">Отправить</button>
        </form>
        <div id="form-success" style="display:none; color:green;">Спасибо! Отзыв добавлен.</div>
    </div>

    <hr>

    <div id="reviews-list">
        @foreach ($reviews as $review)
            @include('reviews._review', ['review' => $review])
        @endforeach
    </div>

    <div id="load-more-area">
        @if($loaded < $total)
            <button id="load-more">Показать ещё</button>
        @else
            <button id="load-more" disabled>Показать ещё</button>
        @endif
    </div>

    <script>
    (function(){
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let offset = {{ $loaded }}; // уже загружено
        const perPage = {{ $perPage }};
        const total = {{ $total }};

        // Функция для отображения ошибок
        function showErrors(errors) {
            ['name','body','rating'].forEach(k => {
                const el = document.getElementById('error-' + k);
                if (el) el.textContent = (errors && errors[k]) ? errors[k].join(', ') : '';
            });
        }

        // submit review
        const form = document.getElementById('review-form');
        form.addEventListener('submit', async function(e){
            e.preventDefault();
            showErrors(null);
            const data = {
                name: document.getElementById('name').value,
                body: document.getElementById('body').value,
                rating: document.getElementById('rating').value,
            };

            try {
                const resp = await fetch("{{ route('reviews.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const json = await resp.json();

                if (!resp.ok) {
                    // Laravel возвращает 422 с errors
                    if (json.errors) {
                        showErrors(json.errors);
                    } else {
                        alert('Ошибка при отправке');
                    }
                    return;
                }

                // Успех: добавим возвращённый HTML в начало списка
                if (json.rendered) {
                    const list = document.getElementById('reviews-list');
                    const div = document.createElement('div');
                    div.innerHTML = json.rendered;
                    list.insertBefore(div.firstElementChild, list.firstChild);
                    // обновить offset и total
                    offset += 1;
                    document.getElementById('form-success').style.display = 'block';
                    form.reset();
                }
            } catch (err) {
                console.error(err);
                alert('Network error');
            }
        });

        // load more
        const loadMoreBtn = document.getElementById('load-more');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', async function(){
                loadMoreBtn.disabled = true;
                loadMoreBtn.textContent = 'Загрузка...';

                try {
                    const url = "{{ route('reviews.more') }}" + "?offset=" + offset;
                    const resp = await fetch(url, { headers: { 'Accept': 'application/json' }});
                    const json = await resp.json();

                    if (json.success) {
                        const container = document.getElementById('reviews-list');
                        // append HTML
                        const tmp = document.createElement('div');
                        tmp.innerHTML = json.html;
                        while (tmp.firstChild) {
                            container.appendChild(tmp.firstChild);
                        }

                        offset += json.count;

                        if (offset >= total || json.count === 0) {
                            loadMoreBtn.style.display = 'none';
                        } else {
                            loadMoreBtn.disabled = false;
                            loadMoreBtn.textContent = 'Показать ещё';
                        }
                    } else {
                        alert('Ошибка получения отзывов');
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.textContent = 'Показать ещё';
                    }
                } catch (err) {
                    console.error(err);
                    alert('Network error');
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Показать ещё';
                }
            });
        }
    })();
    </script>
</body>
</html>
