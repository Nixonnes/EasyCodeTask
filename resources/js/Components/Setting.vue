<template>
    <div>
        <!-- Форма с настройкой пользователя -->
        <InputLabel>Имя</InputLabel>
        <TextInput v-model="form.name"/>
        <PrimaryButton @click="showModalWindow">Save</PrimaryButton>
        <!-- Добавляем компонент модального окна с выбором метода подтверждения -->
        <ModalConfirmation v-if="showModal" @confirm="confirmMethod"></ModalConfirmation>
        <CodeConfirmation v-if="showConfirmationWindow" :name = this.form.name :confirmationCode = this.confirmationCode></CodeConfirmation>
    </div>
</template>

<script>
import ModalConfirmation from "@/Components/ModalConfirmation.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CodeConfirmation from "@/Components/CodeConfirmation.vue";

export default {
    components: {CodeConfirmation, PrimaryButton, TextInput, InputLabel, ModalConfirmation },
    data() {
        return {
            form: {
                name: '',
            },
            showModal: false,
            confirmationCode: null,
            showConfirmationWindow:false,

        };
    },
    methods: {
        showModalWindow() {
            this.showModal = true;
        },
        confirmMethod(data) {
            // Отправляем данные на сервер в зависимости от выбранного метода подтверждения
            if (data.method === 'sms') {
                this.sendConfirmation('/confirm/sms', { phoneNumber: data.phoneNumber });
            } else if (data.method === 'telegram') {
                this.sendConfirmation('/confirm/telegram', { phoneNumber: data.phoneNumber, telegramId: data.telegramId });
            } else {
                // Для Email нет дополнительных данных
                this.sendConfirmation('/confirm/email');
            }
        },

        async sendConfirmation(route, payload = {}) {
            try {
                const response = await this.$axios.post(route, payload);
                if (response.data.success) {
                    this.showConfirmationWindow = true;
                    this.confirmationCode = response.data.confirmation_code;
                } else {
                    console.error('Ошибка при отправке кода подтверждения в Telegram:', response.data.error);
                }
            } catch (error) {
                console.error('Ошибка при отправке запроса:', error);
            }
        }
    }
};
</script>

<style scoped>

</style>
