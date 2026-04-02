import { api } from "./Api";

export const CompanyService = {
  async getCompanies() {
    try {
      const response = await api.get("/companies");
      return response.data;
    } catch (error) {
      console.error("Erro ao buscar empresas:", error);
      throw error;
    }
  },

  async getCompanyById(id) {
    try {
      const response = await api.get(`/companies/${id}`);
      return response.data;
    } catch (error) {
      console.error(`Erro ao buscar empresa ${id}:`, error);
      throw error;
    }
  },

  async createCompany(company) {
    try {
      const response = await api.post("/companies", company);
      return response.data;
    } catch (error) {
      console.error("Erro ao criar empresa:", error);
      throw error;
    }
  },

  async updateCompany(id, company) {
    try {
      const response = await api.put(`/companies/${id}`, company);
      return response.data;
    } catch (error) {
      console.error("Erro ao atualizar empresa:", error);
      throw error;
    }
  },

  async deleteCompany(id) {
    try {
      await api.delete(`/companies/${id}`);
    } catch (error) {
      console.error("Erro ao deletar empresa:", error);
      throw error;
    }
  },
}