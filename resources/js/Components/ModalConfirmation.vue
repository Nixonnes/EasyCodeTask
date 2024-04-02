<template>
    <div v-if="showModal" class="modal">
        <div class="modal-content">
            <h2>Выберите способ подтверждения</h2>
            <label>
                <input type="radio" v-model="selectedMethod" value="sms"> SMS
            </label>
            <label>
                <input type="radio" v-model="selectedMethod" value="email"> Email
            </label>
            <label>
                <input type="radio" v-model="selectedMethod" value="telegram"> Telegram
            </label>
            <!-- Добавляем дополнительное поле для ввода номера телефона или ID в Telegram -->
            <template v-if="selectedMethod === 'sms' ">
                <label>
                    Номер телефона:
                    <input type="text" v-model="phoneNumber">
                </label>
            </template>
            <template v-if="selectedMethod === 'telegram'">
                <label>
                    ID в Telegram:
                    <input type="text" v-model="telegramId">
                </label>
            </template>
            <button @click="confirmMethod">Подтвердить</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            showModal: true,
            selectedMethod: '',
            phoneNumber: '', // Номер телефона
            telegramId: '', // ID в Telegram
        };
    },
    methods: {
        confirmMethod() {
            // Проверяем, выбран ли какой-либо метод подтверждения
            if (!this.selectedMethod) {
                alert('Выберите метод подтверждения');
                return;
            }

            // В зависимости от выбранного метода, передаем соответствующие данные
            if (this.selectedMethod === 'sms') {
                // Вызываем метод для отправки выбранного метода и номера телефона на сервер
                this.$emit('confirm', { method: this.selectedMethod, phoneNumber: this.phoneNumber });
            } else if (this.selectedMethod === 'telegram') {
                // Вызываем метод для отправки выбранного метода, ID в Telegram и номера телефона на сервер
                this.$emit('confirm', { method: this.selectedMethod, telegramId: this.telegramId, phoneNumber: this.phoneNumber });
            } else {
                // Если выбран метод Email, можно просто подтвердить без дополнительных данных
                this.$emit('confirm', { method: this.selectedMethod });
            }

            // Закрываем модальное окно
            this.showModal = false;
        }
    }
};
</script>

<style scoped>
/* Стили для модального окна */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
}
</style>
