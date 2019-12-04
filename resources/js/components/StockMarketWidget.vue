<template>
    <div v-if="data.companyName" class="w-70vw shadow-2xl text-center text-white bg-green-400 pb-5">
        <div class="text-2xl bg-green-600 border-green-800">
            Most Active Companies
        </div>

        <company-name :value="data.companyName" class="text-4xl pt-5"/>
        <current-price :value="data.currentPrice" class="text-6xl p-10"/>
        <update-time :value="data.updateTime" class="text-xl"/>
    </div>

    <div v-else>
        Loading...
    </div>
</template>

<script>
    import CompanyName from '../components/CompanyName';
    import CurrentPrice from '../components/CurrentPrice';
    import UpdateTime from '../components/UpdateTime';

    export default {
        components: {
            CompanyName,
            CurrentPrice,
            UpdateTime
        },

        data() {
            return {
                data: {
                    companyName: null,
                    currentPrice: null,
                    updateTime: null
                }
            }
        },

        mounted() {
            this.getRandomMostActiveCompanyStockPrice();

            setInterval(() => this.getRandomMostActiveCompanyStockPrice(), 10000);
        },

        methods: {
            getRandomMostActiveCompanyStockPrice() {
                axios.get('/api/random-most-active-company-stock-price')
                    .then(response => this.data = response.data)
                    .catch(error => console.log(error))
            }
        }
    }
</script>

<style scoped>
    .w-70vw {
        width: 50vw;
    }
</style>