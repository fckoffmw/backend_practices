<h1><?= htmlspecialchars($title) ?></h1>

<div style="text-align: center; padding: 40px;">
    <h2>Нет данных для отображения статистики</h2>
    <p>Для просмотра графиков и аналитики необходимо сначала сгенерировать тестовые данные.</p>
    
    <div style="margin: 30px 0;">
        <form method="POST" action="/statistics/generate-fixtures">
            <div style="margin-bottom: 20px;">
                <label for="count">Количество записей для генерации:</label><br>
                <input type="number" id="count" name="count" value="50" min="10" max="1000" 
                       style="padding: 10px; font-size: 16px; width: 120px; margin: 10px;">
            </div>
            
            <button type="submit" class="btn" style="font-size: 18px; padding: 12px 24px;">
                Сгенерировать данные
            </button>
        </form>
    </div>
    
    <div style="background: #e9ecef; padding: 20px; border-radius: 8px; margin: 20px auto; max-width: 600px;">
        <h4>Что будет создано:</h4>
        <ul style="text-align: left; margin: 10px 0;">
            <li>Записи о продажах с 9 полями (товар, категория, цена, количество, выручка, дата, регион, покупатель, email)</li>
            <li>Данные за последний год с использованием библиотеки Faker</li>
            <li>5 регионов и 6 категорий товаров</li>
            <li>Реалистичные цены от 100 до 50,000 рублей</li>
        </ul>
    </div>
</div>