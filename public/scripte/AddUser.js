
export default {
  data() {
    return {
      nom_complet: '',
      fonction: '',
      poste: '',
      statut: '',
      email: '',
      mot_de_passe: '',
      error: null,
      success: false,
    };
  },
  methods: {
    async add_user() {
      try {
        const response = await this.$axios.post('AddUser.php', {
          nom_complet: this.nom_complet,
          fonction: this.fonction,
          poste: this.poste,
          statut: this.statut,
          email: this.email,
          mot_de_passe: this.mot_de_passe,
        });

        if (response.data.success) {
          this.success = true;
          this.error = null;
          this.nom_complet = '';
          this.fonction = '';
          this.poste = '';
          this.statut = '';
          this.email = '';
          this.mot_de_passe = '';
        } else {
          this.error = response.data.error;
        }
      } catch (err) {
        this.error = 'An error occurred while registering the user: ' + err.message;
      }
    },
  },
};
