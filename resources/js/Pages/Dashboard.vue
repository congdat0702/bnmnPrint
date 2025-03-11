<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex-container">
                    <div class="p-6 text-gray-900 form-container">
                        <div class="current-sender" @click="showEditSenderModal"
                            style="cursor: pointer; border: 1px solid #ccc; padding: 8px; border-radius: 5px; margin-bottom: 10px;">
                            <p><strong>Người gửi:</strong>
                                <span class="highlight" style="font-size: 13px;">{{ senderInfo.name || '' }}</span>
                            </p>
                        </div>

                        <ShipmentForm :form.sync="formData" @submitted="fetchContacts"
                            @contactSelected="selectContact" />
                        <SuccessMessage :message="successMessage" />
                    </div>

                    <div class="separator"></div>

                    <div class="p-6 text-gray-900 data-display" ref="printSection">
                        <ReceiverInfo :formData="formData" @printContent="printAndSave" />
                    </div>

                    <div class="separator"></div>

                    <ContactList :contacts="contacts" :searchQuery="searchQuery" :filteredContacts="filteredContacts"
                        @filterContacts="filterContacts" @contactSelected="selectContact" />

                </div>
            </div>
        </div>

        <EditSenderModal :isVisible="isEditSenderModalVisible" :senderInfo="senderInfo" :senders="senders"
            :saveSender="saveSenderInfo" :selectSender="selectSender" :close="hideEditSenderModal" />

    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ShipmentForm from '@/Components/ShipmentForm.vue';
import EditSenderModal from '@/Components/EditSenderModal.vue';
import ContactList from '@/Components/ContactList.vue';
import ReceiverInfo from '@/Components/ReceiverInfo.vue';
import SuccessMessage from '@/Components/SuccessMessage.vue';
import axios from 'axios';

export default {
    components: {
        AuthenticatedLayout,
        ShipmentForm,
        EditSenderModal,
        ContactList,
        ReceiverInfo,
        SuccessMessage,
    },
    data() {
        return {
            senderInfo: {
                name: 'CỬA HÀNG BƠM NƯỚC MINI VẠN THẮNG',
                contact: 'NGUYỄN ĐỨC THẮNG | 0977666881 - 0902681015',
                address: '159/8 NGÔ QUYỀN, P6, Q10, TPHCM'
            },
            selectedSender: null,
            senders: [],
            formData: {
                name: '',
                phone: '',
                address: '',
                company: '',
                cod: '',
                item: '',
                payer: ''
            },
            contacts: [],
            searchQuery: '',
            filteredContacts: [],
            successMessage: '',
            isEditSenderModalVisible: false,
        };
    },
    mounted() {
        this.fetchContacts();
        this.loadSenders();
    },
    methods: {
        loadSenders() {
            axios.get('/api/senders')
                .then(response => {
                    this.senders = response.data;
                })
                .catch(error => {
                    console.error('Có lỗi khi lấy người gửi:', error);
                });
        },
        fetchContacts() {
            axios.get('/api/contacts')
                .then(response => {
                    this.contacts = response.data.sort((a, b) => b.id - a.id);
                    this.filteredContacts = this.contacts.slice(0, 15);
                })
                .catch(error => {
                    console.error('Có lỗi khi lấy danh bạ:', error);
                });
        },
        saveSenderInfo() {
            if (this.senderInfo.name && this.senderInfo.contact && this.senderInfo.address) {
                axios.post('/api/senders', this.senderInfo)
                    .then(response => {
                        this.senders.push(response.data);
                        this.clearSenderInfo();
                        this.hideEditSenderModal();
                    })
                    .catch(error => {
                        console.error('Có lỗi khi lưu người gửi:', error);
                    });
            } else {
                alert("Vui lòng điền đầy đủ thông tin");
            }
        },
        selectContact(contact) {
            this.formData = { ...contact };
        },
        filterContacts(query) {
            this.searchQuery = query;
            const lowerQuery = query.toLowerCase();
            this.filteredContacts = this.contacts.filter(contact =>
                contact.name.toLowerCase().includes(lowerQuery) || contact.phone.includes(lowerQuery)
            ).slice(0, 15);
        },
        async printAndSave() {
            try {
                await axios.post('/api/print-history', {
                    recipient_name: this.formData.name,
                    recipient_phone: this.formData.phone,
                    printed_by: 'User1'
                });
                this.printContent();
            } catch (error) {
                console.error('Error saving print history:', error);
            }
        },
        printContent() {
            const senderInfo = `
        <p class="print-bold">${this.senderInfo.name}</p>
        <p class="print-bold">${this.senderInfo.contact}</p>
        <p class="print-bold">Địa chỉ: ${this.senderInfo.address}</p>      `;
            const receiverInfo = `
        ${this.formData.name ? `<p class="print-bold print-large">TÊN: ${this.formData.name.toUpperCase()}</p>` : ''}
        ${this.formData.phone ? `<p class="print-large"><strong>SĐT:</strong> <span class="print-italic">${this.formData.phone}</span></p>` : ''}
        ${this.formData.address ? `<p class="print-large"><strong>ĐỊA CHỈ:</strong> <span class="print-italic">${this.formData.address.toUpperCase()}</span></p>` : ''}
        ${this.formData.company ? `<p class="print-italic"><strong>ĐVVC:</strong> ${this.formData.company}</p>` : ''}
        ${this.formData.cod ? `<p class="print-italic"><strong>COD:</strong> ${this.formData.cod}</p>` : ''}
        ${this.formData.item ? `<p class="print-italic"><strong>TÊN HÀNG:</strong> ${this.formData.item}</p>` : ''}
        ${this.formData.payer ? `<p class="print-italic"><strong>TIỀN CƯỚC:</strong> ${this.formData.payer}</p>` : ''}
      `;
            const content = `
        <html>
          <head>
            <style>
              body {
                font-family: Arial, sans-serif;
                font-size: 10px;
                margin: 0;
                padding: 0;
              }
              .mainPrints {
                width: 105mm;
                height: 74mm;
                background: white;
                border: solid 1px #000;
                padding: 3mm;
                box-sizing: border-box;
                font-size: 10px;
                font-weight: 500;
                position: relative;
                margin: auto;
              }
              @media print {
                .mainPrints {
                  page-break-after: always;
                }
                .mainPrints:last-of-type {
                  page-break-after: avoid;
                }
                @page {
                  size: 105mm 74mm;
                  margin: 0;
                }
              }
              .separator {
                border-top: 1px solid #000;
                margin: 2mm 0;
              }
              .print-bold {
                font-weight: bold;
                font-size: 15px;
                margin: 2px;
              }
              .print-large {
                font-size: 18px;
                margin: 4px;
              }
              .print-small, .print-italic {
                font-size: 16px;
                margin: 3px;
                font-style: italic;
              }
            </style>
          </head>
          <body>
            <div class="mainPrints">
              <div class="section">${senderInfo}</div>
              <div class="separator"></div>
              <div class="section">${receiverInfo}</div>
            </div>
            <script>
              window.print();
              window.onafterprint = function() { window.close(); }
            <\/script>
          </body>
        </html>
      `;

            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(content);
            printWindow.document.close();
            printWindow.focus();
        },
        showEditSenderModal() {
            this.isEditSenderModalVisible = true;
        },
        hideEditSenderModal() {
            this.isEditSenderModalVisible = false;
        },
        clearSenderInfo() {
            this.senderInfo = { name: '', contact: '', address: '' };
        },
        selectSender(sender) {
            this.senderInfo = { ...sender };
            this.hideEditSenderModal();
        },
    },
};
</script>

<style scoped>
.flex-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.form-container {
    flex: 4 1 40%;
    padding: 20px;
}

.separator {
    width: 1px;
    background-color: #ccc;
    margin: 0 10px;
    height: auto;
}

.data-display {
    flex: 3 1 30%;
}

.data-display p {
    margin: 3px 0;
}
</style>