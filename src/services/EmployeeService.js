import { api } from "./Api";

export const EmployeeService = {
  async getEmployees() {
    try {
      const response = await api.get("/employees");
      return response.data;
    } catch (error) {
      console.error("Erro ao buscar funcionários:", error);
      throw error;
    }
  },

  async getEmployeeById(id) {
    try {
      const response = await api.get(`/employees/${id}`);
      return response.data;
    } catch (error) {
      console.error(`Erro ao buscar funcionário ${id}:`, error);
      throw error;
    }
  },

  async createEmployee(company) {
    try {
      const response = await api.post("/employees", company);
      return response.data;
    } catch (error) {
      console.error("Erro ao criar funcionário:", error);
      throw error;
    }
  },

  async updateEmployee(id, company) {
    try {
      const response = await api.put(`/employees/${id}`, company);
      return response.data;
    } catch (error) {
      console.error("Erro ao atualizar funcionário:", error);
      throw error;
    }
  },

  async deleteEmployee(id) {
    try {
      await api.delete(`/employees/${id}`);
    } catch (error) {
      console.error("Erro ao deletar funcionário:", error);
      throw error;
    }
  },
}