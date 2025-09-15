import React from "react";
import { StyleSheet, Text, View, Button } from "react-native";
import { useNavigation } from "@react-navigation/native";

export default function Home() {
  const navegacao = useNavigation();

  return (
    <View style={styles.container}>
      <Text>Tela HOME</Text>
      <Button
        title="Ir para Estados"
        onPress={() => navegacao.navigate("Estados")}
      />
      <Button
        title="Ir para Alunos"
        onPress={() => navegacao.navigate("Alunos")}
      />
      <Button
        title="Ir para Cursos"
        onPress={() => navegacao.navigate("Cursos")}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#fff",
    alignItems: "center",
    justifyContent: "center",
    gap: 14,
  },
});
