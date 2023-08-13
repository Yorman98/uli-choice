export interface ProductInterface {
    id: number;
    name: string;
    slug: string;
    code: string;
    description?: string;
    price: number;
    stock: number;
    image: string;
    unit_cost: number;
    unit_price: number;
    groups: string[]
    attributes: string[]
    categories: [];
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}
