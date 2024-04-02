<template>
    <div class="modal">
        <div class="modal-content">
            <h2>Введите код подтверждения</h2>
            <input type="text" v-model="enteredConfirmationCode">
            <button @click="confirmCode">Подтвердить</button>
        </div>
    </div>
</template>

<script>
export default  {
    props: {
        confirmationCode:String,
        name:String,
    },
    data() {
        return {
            enteredConfirmationCode: '',
        }
    },
    methods: {
        confirmCode() {
            console.log(this.confirmationCode);
            if (this.enteredConfirmationCode === this.confirmationCode) {
                axios.post('/update-name', {
                    name : this.name
                })
            } else {
                alert('Неверный код подтверждения');
            }
        },
    }
}
</script>
